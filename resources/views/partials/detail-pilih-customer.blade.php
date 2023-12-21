<div id="pilihCustomer" class="detail-pilih-customer">
    <input type="hidden" name="slug_customer" id="slug">
    <div class="row">
        <div class="col-sm-2 d-flex justify-content-between">
            <div>Nama</div><div>:</div>
        </div>
        <div class="col-sm-auto ps-0" id="nama">@isset($data->customer) {{ $data->customer->nama }} @endisset</div>
    </div>
    <div class="row">
        <div class="col-sm-2 d-flex justify-content-between">
            <div>Alamat</div><div>:</div>
        </div>
        <div class="col-sm-auto ps-0" id="alamat">@isset($data->customer) {{ $data->customer->alamat }} @endisset</div>
    </div>
    <div class="row">
        <div class="col-sm-2 d-flex justify-content-between">
            <div>No.Telepon</div><div>:</div>
        </div>
        <div class="col-sm-auto ps-0" id="telepon">@isset($data->customer) {{ $data->customer->telepon }} @endisset</div>
    </div>
    <div class="row">
        <div class="col-sm-2 d-flex justify-content-between">
            <div>Email</div><div>:</div>
        </div>
        <div class="col-sm-auto ps-0" id="email">@isset($data->customer) {{ $data->customer->email }} @endisset</div>
    </div>
    <div class="row">
        <div class="col-sm-2 d-flex justify-content-between">
            <div>Penawaran Terakhir</div><div>:</div>
        </div>
        <div class="col-sm-auto ps-0" id="tglPenawaranTerakhir"></div>
    </div>
</div>
