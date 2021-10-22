const groupBy = (xs, key) =>
    xs.reduce((rv, x) => {
        (rv[x[key]] = rv[x[key]] || []).push(x);
        return rv;
    }, {});

const pembangunanTalikuatByUptd = groupBy(pembangunanTalikuat, "id_uptd");

const detailSeriesCurva = {};
Object.keys(detailDataTalikuat).map(async(key) => {
    detailSeriesCurva[key] = generateCurva(detailDataTalikuat[key])
})

let categories = { UPTD: Object.keys(pembangunanTalikuatByUptd).map(key => `UPTD ${key}`) };

const series = [{
    name: "UPTD",
    colorByPoint: true,
    stacking: 'normal',
    data: Object.keys(pembangunanTalikuatByUptd).map(key => {
        return {
            name: `UPTD-${key}`,
            y: pembangunanTalikuatByUptd[key].length,
            // persentase: (pembangunanTalikuatByUptd[key].length / pembangunanTalikuat.length) * 100,
            drilldown: `UPTD-${key}`,
            dataLabels: {
                enabled: true,
                format: "{point.y} Paket",
            },
        }
    }),
    tooltip: {
        headerFormat: '<span style="font-size:11px">Jumlah Paket Pekerjaan {point.name}</span><br>',
        pointFormat: '<span style="color:{point.color}"><b>{point.y}</b><br/>',
    },
}, ];

const seriesDrildownUptd =
    Object.keys(pembangunanTalikuatByUptd).map(key => {
        categories[`UPTD-${key}`] = pembangunanTalikuatByUptd[key].map(data => data.nm_paket)
        return {
            name: `UPTD-${key}`,
            id: `UPTD-${key}`,
            data: pembangunanTalikuatByUptd[key].map(data => {
                const progres = dataTalikuat.find(_data => _data.id_data_umum == data.id)
                const persentase = Number(Number(progres ? progres.persentase : 0).toFixed(2))
                return {
                    name: data.nm_paket,
                    y: persentase,
                    dataLabels: {
                        enabled: true,
                        format: "{point.y}%",
                    },
                    drilldown: `CURVA-${data.id}`,
                }
            }),
            tooltip: {
                // headerFormat: '<span style="font-size:11px">Persentase progres paket {point.name}</span><br>',
                pointFormat: '<span style="color:{point.color}">Progres <b>{point.y}%</b><br/>',
            },
        }
    })

const curvaDrilldown = [];
Object.keys(detailSeriesCurva).map(key => {
    categories[`CURVA-${key}`] = detailSeriesCurva[key].labels
    curvaDrilldown[`CURVA-${key}`] = {
        name: `CURVA-${key}`,
        id: `CURVA-${key}`,
        color: '#00FF00',
        data: detailSeriesCurva[key].rencana.map(val => Number(Number(val).toFixed(2))),
        type: 'line',
        tooltip: {
            headerFormat: '<span style="font-size:11px">{point.key}</span><br>',
            pointFormat: '<span style="color:{point.color}">Progres <b>{point.y}%</b><br/>',
        },
    }
})

Object.keys(detailSeriesCurva).map(key => {
    curvaDrilldown[`CURVA-${key}-2`] = {
        name: `CURVA-${key}-2`,
        id: `CURVA-${key}-2`,
        data: detailSeriesCurva[key].realisasi.map(val => Number(Number(val).toFixed(2))),
        type: 'line',
        tooltip: {
            headerFormat: '<span style="font-size:11px">{point.key}</span><br>',
            pointFormat: '<span style="color:{point.color}">Progres <b>{point.y}%</b><br/>',
        },
    }
})

console.log(categories)


const defaultTitle = 'Jumlah Paket Pembangunan';
const drilldownTitle = 'Persentase Progres'

var chart = Highcharts.chart("container_pembangunan_talikuat_uptd", {
    chart: {
        type: "column",
        events: {
            drilldown: function(e) {
                chart.setTitle({ text: `${drilldownTitle} ${e.point.name}` });
                console.log(chart.xAxis[0].paddedTicks)
                if (!e.seriesOptions) {
                    drilldowns = curvaDrilldown
                    let seriesTmp = [drilldowns[e.point.drilldown], drilldowns[e.point.drilldown + '-2']];
                    chart.addSingleSeriesAsDrilldown(e.point, seriesTmp[0]);
                    chart.addSingleSeriesAsDrilldown(e.point, seriesTmp[1]);
                    chart.applyDrilldown();
                    chart.xAxis[0].setCategories(chart.xAxis[0].paddedTicks.map(val => `Minggu ke-${val + 1}`))
                    chart.series[0].update({ name: "Rencana" });
                    chart.series[1].update({ name: "Realisasi" });
                } else
                    chart.xAxis[0].setCategories(e.seriesOptions.data.map(data => data.name))
            },
            drillup: function(e) {
                chart.setTitle({ text: defaultTitle });
                // chart.xAxis[0].setCategories(null)
                chart.xAxis[0].setCategories(e.seriesOptions.data.map(data => data.name))
                console.log(e, 'eeeeeeeeeeee')
            }
        }
    },
    title: {
        text: defaultTitle,
    },
    subtitle: {
        text: "Klik pada chart untuk melihat lebih detail",
    },
    accessibility: {
        announceNewData: {
            enabled: true,
        },
    },
    xAxis: {
        type: "category",
    },
    // yAxis: {
    //     title: {
    //         text: "Total Pembangunan",
    //     },
    // },
    legend: {
        enabled: true,
    },
    plotOptions: {
        series: {
            borderWidth: 0,
        },
    },

    series,
    drilldown: {
        series: [...seriesDrildownUptd]
    },
});


function sum(arr, prop) {
    let total = 0;
    for (let i = 0, _len = arr.length; i < _len; i++) {
        total += parseFloat(arr[i][prop], 0);
    }
    return total;
};

function generateCurva(res) {
    let data = [];
    let week;
    let tglWeek = [];
    let dataSum = [];
    let lapSum = [];
    const jmlMinggu = [];

    const lapPerMinggu = [];
    const dataPerMinggu = [];
    let laporan = [];
    const termin = res.data_umum[0].lama_waktu / 7;

    if (Number.isInteger(termin)) {
        week = termin;
    } else {
        week = parseInt(termin + 1);
    }

    const tglSpmk = res.data_umum[0].tgl_spmk;
    Date.prototype.addDays = function(days) {
        var date = new Date(this.valueOf());
        date.setDate(date.getDate() + days);
        return date;
    };
    let date = new Date(tglSpmk);

    let u = 0;
    for (let i = 0; i < week; i++) {
        if (tglWeek.length == 0) {
            tglWeek.push(date.toISOString().slice(0, 10));
        } else {
            tglWeek.push(
                convertDate(tglWeek[u]).addDays(7).toISOString().slice(0, 10)
            );
            u++;
        }
    }

    let minggu = 1;
    for (let i = 0; i < tglWeek.length; i++) {
        res.curva.forEach((arr) => {
            const tesData = getByWeek(
                arr,
                convertDate(tglWeek[i]).toISOString().slice(0, 10),
                convertDate(tglWeek[i]).addDays(6).toISOString().slice(0, 10)
            );
            for (let i = 0; i < tesData.length; i++) {
                if (tesData[i].length != 0) {
                    data.push(tesData[i]);
                }
            }
        });
        res.laporan.forEach((arr) => {
            const lap = getLaporan(
                arr,
                convertDate(tglWeek[i]).toISOString().slice(0, 10),
                convertDate(tglWeek[i]).addDays(6).toISOString().slice(0, 10)
            );
            for (let i = 0; i < lap.length; i++) {
                if (lap[i].length != 0) {
                    laporan.push(lap[i]);
                }
            }
        });

        lapPerMinggu.push(laporan);
        dataPerMinggu.push(data);
        dataSum.push(sum(data, "nilai"));
        lapSum.push(sum(laporan, "bobot") + (lapSum[i - 1] || 0));
        laporan = [];
        data = [];
        jmlMinggu.push("Minggu Ke " + minggu);
        minggu++;
    }

    const cumulativeSum = (
        (sum) => (value) =>
        (sum += value)
    )(0);

    const sumCumulative = dataSum.map(cumulativeSum);
    for (let i = 0; i < sumCumulative.length; i++) {
        sumCumulative[i] = parseFloat(sumCumulative[i]).toFixed(3);
    }

    let cut = 0;
    let temp = 0;
    lapSum.forEach((data, key) => {
        if (temp < data) {
            cut = key;
        }
        temp = data;
    });

    return {
        labels: jmlMinggu,
        rencana: sumCumulative,
        realisasi: lapSum.splice(0, cut)
    }
}


function getByWeek(arr, start, end) {
    return arr.filter((data) => data.tgl >= start && data.tgl <= end);
}

function getLaporan(array, start, end) {
    return array.filter((lap) => lap.tanggal >= start && lap.tanggal <= end);
}

function sumData(arr) {
    let sum = 0;
    for (let i = 0; i < arr.length; i++) {
        sum += arr[i].reduce((s, a) => s + parseFloat(a.nilai), 0);
    }
    return sum;
}

function convertDate(date) {
    return new Date(date);
}


function sumLapHarian(arr, key) {
    let sum = [];
    arr.map((data) => {
        let tmp = 0;
        data.map((_data, idx) => {
            tmp += Number(_data[key]);
        });
        sum.push(tmp);
    });
    return sum;
}