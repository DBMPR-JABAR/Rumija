const forms = [{
        nama: "Pemasangan Utilitas",
        kode: "PU",
        forms: [{
                label: "Surat permohonan diatas Kop Surat ditunjukan kepada Kepala Dinas PMPTSP Prov. Jawa Barat (Kop surat asli, tandatangan asli bukan scan/palsu serta cap/stempel asli bukan scan)",
                type: "file",
                kode: 1,
            },
            {
                label: "Surat Kuasa memakai kop surat diatas materai apabila dikuasakan (kop surat asli, tandatangan asli bukan scan, cap/stempel asli bukn scan)",
                type: "file",
                kode: 2,
            },
            {
                label: "Fotocopy KTP Permohonan",
                type: "file",
                kode: 3,
            },
            {
                label: "NIK KTP Permohon",
                type: "number",
                kode: 31,
            },
            {
                label: "Nama KTP Permohon",
                type: "text",
                kode: 32,
            },
            {
                label: "Alamat KTP Permohon",
                type: "text",
                kode: 33,
            },
            {
                label: "Fotocopy KTP yang dikuasakan",
                type: "file",
                kode: 4,
            },
            {
                label: "NIK KTP yang dikuasakan",
                type: "number",
                kode: 41,
            },
            {
                label: "Nama KTP yang dikuasakan",
                type: "text",
                kode: 42,
            },
            {
                label: "Alamat KTP yang dikuasakan",
                type: "text",
                kode: 43,
            },
            {
                label: "Fotocopy Akte Pendirian Persuahaan sesuai dengan nama di permohonan (apabila pemohon perusahaan)/ Copy KTP untuk Perorangan",
                type: "file",
                kode: 5,
            },
            {
                label: "Surat Pernyataan Kesanggupan survey lapangan",
                type: "file",
                kode: 6,
            },
            {
                label: "Jadwal pelaksanaan pekerjaan",
                type: "file",
                kode: 7,
            },
            {
                label: "Surat Pernyataan bersedia memberikan jaminan bank garansi",
                type: "file",
                kode: 8,
            },
            {
                label: "Surat Pernyataan Pemakaian Tanah Negara bermaterai Rp. 6000",
                type: "file",
                kode: 9,
            },
        ],
    },
    {
        nama: "Jalan Masuk",
        kode: "JM",
        forms: [{
                label: "Surat permohonan diatas Kop Surat ditunjukan kepada Kepala Dinas PMPTSP Prov. Jawa Barat (Kop surat asli, tandatangan asli bukan scan/palsu serta cap/stempel asli bukan scan)",
                type: "file",
                kode: 1,
            },
            {
                label: "Surat Kuasa memakai kop surat diatas materai apabila dikuasakan (kop surat asli, tandatangan asli bukan scan, cap/stempel asli bukn scan)",
                type: "file",
                kode: 2,
            },
            {
                label: "Fotocopy KTP Permohon",
                type: "file",
                kode: 3,
            },
            {
                label: "NIK KTP Permohon",
                type: "number",
                kode: 31,
            },
            {
                label: "Nama KTP Permohon",
                type: "text",
                kode: 32,
            },
            {
                label: "Alamat KTP Permohon",
                type: "text",
                kode: 33,
            },
            {
                label: "Fotocopy KTP yang dikuasakan",
                type: "file",
                kode: 4,
            },
            {
                label: "NIK KTP yang dikuasakan",
                type: "number",
                kode: 41,
            },
            {
                label: "Nama KTP yang dikuasakan",
                type: "text",
                kode: 42,
            },
            {
                label: "Alamat KTP yang dikuasakan",
                type: "text",
                kode: 43,
            },
            {
                label: "Fotocopy Akte Pendirian Persuahaan sesuai dengan nama di permohonan (apabila pemohon perusahaan)/ Copy KTP untuk Perorangan",
                type: "file",
                kode: 5,
            },
            {
                label: "Surat Pernyataan Kesanggupan survey lapangan",
                type: "file",
                kode: 6,
            },
            {
                label: "Gambar Situasi (1)",
                type: "file",
                kode: 7,
            },
            {
                label: "Gambar Situasi (2) (optional)",
                type: "file",
                kode: 71,
                require: false,
            },
            {
                label: "Gambar Situasi (3) (optional)",
                type: "file",
                kode: 72,
                require: false,
            },
            {
                label: "Surat pernyataan Pemakaian Tanah Negara bermaterai Rp. 10.000 (6000 + 6000 atau 6000 + 3000)",
                type: "file",
                kode: 8,
            },
        ],
    },
    {
        nama: "Pemasangan Reklame",
        kode: "PR",
        forms: [{
                label: "Surat pernyataan Pemakaian Tanah Negara bermaterai Rp. 6.000",
                type: "file",
                kode: 1,
            },
            {
                label: "Surat Pernyataan Kesanggupan survey lapangan",
                type: "file",
                kode: 2,
            },
            {
                label: "Surat Kuasa memakai kop surat diatas materai apabila dikuasakan (kop surat asli, tandatangan asli bukan scan, cap/stempel asli bukn scan)",
                type: "file",
                kode: 3,
            },
            {
                label: "Fotocopy KTP Permohon",
                type: "file",
                kode: 4,
            },
            {
                label: "NIK KTP Permohon",
                type: "number",
                kode: 41,
            },
            {
                label: "Nama KTP Permohon",
                type: "text",
                kode: 42,
            },
            {
                label: "Alamat KTP Permohon",
                type: "text",
                kode: 43,
            },
            {
                label: "Fotocopy KTP yang dikuasakan",
                type: "file",
                kode: 5,
            },
            {
                label: "NIK KTP yang dikuasakan",
                type: "number",
                kode: 51,
            },
            {
                label: "Nama KTP yang dikuasakan",
                type: "text",
                kode: 52,
            },
            {
                label: "Alamat KTP yang dikuasakan",
                type: "text",
                kode: 53,
            },
            {
                label: "Fotocopy Akte Pendirian Persuahaan sesuai dengan nama di permohonan(apabila pemohon perusahaan) / Copy KTP untuk Perorangan",
                type: "file",
                kode: 6,
            },
            {
                label: "Gambar Rencana Konstruksi (1)",
                type: "file",
                kode: 7,
            },
            {
                label: "Gambar Rencana Konstruksi (2) (optional)",
                type: "file",
                require: false,
                kode: 71,
            },
            {
                label: "Gambar Rencana Konstruksi (3) (optional)",
                type: "file",
                require: false,
                kode: 72,
            },
        ],
    },
];

document.addEventListener("DOMContentLoaded", () => {
            const { persyaratan } = data || { persyaratan: null };
            console.log(JSON.parse(persyaratan), storageURL);
            const persyaratanParse = JSON.parse(persyaratan);
            const renderPersyaratan = () => {
                    const persyaratanContainer = document.getElementById(
                        "persyaratan_container"
                    );
                    const { value: tipePermohonan } =
                    document.getElementById("tipe_permohonan");
                    const selectedPersyaratanForm = forms.find(
                        (form) => form.kode === tipePermohonan
                    );
                    document.getElementById("selectedPersyaratanForm").value =
                        JSON.stringify(selectedPersyaratanForm);

                    let htmlPersyaratan = selectedPersyaratanForm.forms.map((form) => {
                                return `<div class="form-group row">
            <label class="col-md-${form.type === "file" ? 9 : 3
                } col-form-label">${form.label}</label>
            <div class="col-md-${form.type === "file" ? (action === "update" ? 2 : 3) : 9
                } row">
                    <input name="${selectedPersyaratanForm.kode}_${form.kode
                }" type="${form.type}" ${form.type !== "file" && action === "update"
                    ? persyaratan
                        ? persyaratanParse[
                            `${selectedPersyaratanForm.kode}_${form.kode}`
                        ]
                            ? `value="${persyaratanParse[
                            `${selectedPersyaratanForm.kode}_${form.kode}`
                            ]}"` : '' : '' : ''}
                        class="form-control" ${(form.require === false) || (action === "update") ? '' : 'required'} accept="image/jpeg,image/gif,image/png,application/pdf,image/x-eps">
            </div>
            ${form.type === "file" && action === "update"
                    ? persyaratan
                        ? persyaratanParse[
                            `${selectedPersyaratanForm.kode}_${form.kode}`
                        ]
                            ? `<div class="col-md-1">
                <a href="${storageURL}/${persyaratanParse[
                            `${selectedPersyaratanForm.kode}_${form.kode}`
                            ]
                            }" target="_blank" class="btn btn-primary"><i class="fas fa-share-square"></i></a>
            </div>`
                            : ""
                        : ""
                    : ""
                }
            </div>`;
        });

        persyaratanContainer.innerHTML = htmlPersyaratan.join("");
    };

    const tipePermohonanSelect = document.getElementById("tipe_permohonan");
    tipePermohonanSelect.onchange = () => renderPersyaratan();
    renderPersyaratan();
});

const _validFileExtensions = [".jpg", "jpeg", "png", ".img", ".pdf"];
function Validate(oForm) {
    const arrInputs = oForm.getElementsByTagName("input");
    for (let i = 0; i < arrInputs.length; i++) {
        let oInput = arrInputs[i];
        if (oInput.type == "file") {
            let sFileName = oInput.value;
            if (sFileName.length > 0) {
                let blnValid = false;
                for (let j = 0; j < _validFileExtensions.length; j++) {
                    let sCurExtension = _validFileExtensions[j];
                    if (
                        sFileName
                            .substr(
                                sFileName.length - sCurExtension.length,
                                sCurExtension.length
                            )
                            .toLowerCase() == sCurExtension.toLowerCase()
                    ) {
                        blnValid = true;
                        break;
                    }
                }

                if (!blnValid) {
                    alert(
                        "Maaf, " +
                        sFileName +
                        " tidak valid, hanya diizinkan mengupload gambar/pdf: " +
                        _validFileExtensions.join(", ")
                    );
                    return false;
                }
            }
        }
    }
    return true;
}