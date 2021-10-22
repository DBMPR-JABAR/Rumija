<html>
  <body style="background-color:#e2e1e0;font-family: Open Sans, sans-serif;font-size:100%;font-weight:400;line-height:1.4;color:#000;">
    <div style="padding: 15px;">
      <table style="max-width:600px;margin:50px auto 50px; background-color:#fff;padding:20px;-webkit-border-radius:3px;-moz-border-radius:3px;border-radius:3px;-webkit-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);-moz-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24); border-top: solid 10px #4d90fe;">
        <thead>
          <tr>
              <td colspan="2" style="border-bottom: solid 1px #ddd;padding:5px 0px;text-align: right;">
                <img src="http://citylab.itb.ac.id/temanjabar-laravel/public/assets/images/brand/text_hitam.png" style="width: 108px;height: 25px;">
              </td>
          </tr>
          <tr>
            <th style="text-align:left;">
              <p style="font-size: 13px;">Disposisi Code :</p>
              <h1 style="color:#4d90fe;"> {{$data['disposisi_code'] ?? "XXXXXXXXX"}}</h1>
            </th>
            <th style="text-align:right;font-weight:400;vertical-align:top;font-size: 14px;">
              <p>{{ $data['date_now'] ?? "2020-20-20"}}</p>
            </th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td colspan="2" style="border: solid 1px #ddd; padding:10px 20px;">
              <p style="font-size:14px;margin:0 0 6px 0;"><span style="display:inline-block;min-width:110px">Pengirim</span>
              <b style="color:#4d90fe;font-weight:normal;margin:0">: {{($data['pengirim']) ?? ""}}</b></p>
              <p style="font-size:14px;margin:0 0 6px 0;"><span style="display:inline-block;min-width:110px">Surat Dari</span>
              <b style="color:#4d90fe;font-weight:normal;margin:0">: {{$data['dari'] ?? ""}} </b></p>
              <p style="font-size:14px;margin:0 0 6px 0;"><span style="display:inline-block;min-width:110px">Perihal</span>
              <b style="color:#4d90fe;font-weight:normal;margin:0">:  {{$data['perihal'] ?? ""}} </b></p>
              <p style="font-size:14px;margin:0 0 6px 0;"><span style="display:inline-block;min-width:110px">No Surat</span>
              <b style="color:#4d90fe;font-weight:normal;margin:0">:  {{$data['no_surat'] ?? ""}} </b></p>
              <p style="font-size:14px;margin:0 0 6px 0;"><span style="display:inline-block;min-width:110px">Instruksi</span>
              <b style="color:#4d90fe;font-weight:normal;margin:0">:  {{$data['instruksi'] ?? ""}} </b></p>
              <p style="font-size:14px;margin:0 0 6px 0;"><span style="display:inline-block;min-width:110px">Target Penyelesaian</span>
              <b style="color:#4d90fe;font-weight:normal;margin:0">:  {{$data['tanggal_penyelesaian'] ?? ""}} </b></p>

            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </body>
</html>
