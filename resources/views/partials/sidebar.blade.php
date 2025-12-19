<!-- Sidebar SELURUH
<ul class="navbar-nav bg-primary sidebar sidebar-dark accordion" id="accordionSidebar">
-->
  <!-- Sidebar - Brand
  <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('/home') }}">
    <div class="sidebar-brand-icon rotate-n-15">
    </div>
    <div class="sidebar-brand-text mx-2">Rekapitulasi Perusahaan</div>
  </a>
-->
  <!-- Profile
  <div class="profile text-center mb-3">
    <img class="img-profile rounded-circle w-50" src="{{ asset('ui/svg/rohtek/pngr.png') }}" alt="User Profile">
    <p class="text-white fs-normal">
      {{ auth()->user()->email }} <br>
      <span class="fs-small">{{ auth()->user()->role }} PT Rohtek Amanah Global</span>
    </p>
  </div>
-->
  <!-- Menu items based on user role
  @if (Auth::user()->role == 'Admin')
  Dashboard
  <li class="nav-item {{ Request::is('dashboard') ? 'active' : '' }} border-bottom">
    <a class="nav-link px-5 w-100" href="{{ url('dashboard') }}">
      <i class="fas fa-fw fa-chart-bar"></i>
      <span>Dashboard</span>
    </a>
  </li>
-->
  <!-- Tim Lapangan Section
  <li class="nav-item {{ Request::is('rekapitulasi_pekerjaan*', 'manpowerh*') ? 'active' : '' }} border-bottom">
    <a class="nav-link px-5 collapsed" href="#" data-toggle="collapse" data-target="#collapseTimLapangan" aria-expanded="false" aria-controls="collapseTimLapangan">
      <i class="fas fa-fw fa-users"></i>
      <span>Tim Lapangan Project</span>
    </a>
    <div id="collapseTimLapangan" class="collapse {{ Request::is('rekapitulasi_pekerjaan*', 'manpowerh*') ? 'show' : '' }}" aria-labelledby="headingPages" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header px-3">Rekapitulasi Tim Lapangan</h6>
        <a class="collapse-item {{ Request::is('rekapitulasi_pekerjaan*') ? 'active' : '' }}" href="{{ url('rekapitulasi_pekerjaan') }}">Rekapitulasi Pekerjaan</a>
        <a class="collapse-item {{ Request::is('manpowerh*') ? 'active' : '' }}" href="{{ url('manpowerh') }}">Rekapitulasi Manpower Detail</a>
      </div>
    </div>
  </li>
-->
  <!-- Data Analisa Pekerjaan
  <li class="nav-item {{ Request::is('analisa_pekerjaan*') ? 'active' : '' }} border-bottom">
    <a class="nav-link px-5 collapsed" href="#" data-toggle="collapse" data-target="#collapseMaster" aria-expanded="false" aria-controls="collapseMaster">
      <i class="fas fa-fw fa-industry"></i>
      <span>Data Analisa Pekerjaan</span>
    </a>
    <div id="collapseMaster" class="collapse {{ Request::is('analisa_pekerjaan*') ? 'show' : '' }}" aria-labelledby="headingPages" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header px-3">Daftar Jenis Pekerjaan</h6>
        <a class="collapse-item {{ Request::is('analisa_pekerjaan*') ? 'active' : '' }}" href="{{ url('analisa_pekerjaan') }}">Analisa Pekerjaan</a>
      </div>
    </div>
  </li>
-->
  <!-- Data Marketing
  <li class="nav-item {{ Request::is('spph*', 'spk*', 'item_pekerjaan*', 'progress*') ? 'active' : '' }} border-bottom">
    <a class="nav-link px-5 collapsed" href="#" data-toggle="collapse" data-target="#collapseMarketing" aria-expanded="false" aria-controls="collapseMarketing">
      <i class="fas fa-fw fa-calendar-alt"></i>
      <span>Data Marketing</span>
    </a>
    <div id="collapseMarketing" class="collapse {{ Request::is('spph*', 'spk*', 'item_pekerjaan*', 'progress*') ? 'show' : '' }}" aria-labelledby="headingPages" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header px-3">Rekapitulasi SPPH dan SPK</h6>
        <a class="collapse-item {{ Request::is('spph*') ? 'active' : '' }}" href="{{ url('spph') }}">Incoming SPPH</a>
        <a class="collapse-item {{ Request::is('spk*') ? 'active' : '' }}" href="{{ url('spk') }}">Outgoing SPK</a>
        <a class="collapse-item {{ Request::is('item_pekerjaan*') ? 'active' : '' }}" href="{{ url('item_pekerjaan') }}">Item Pekerjaan</a>
        <a class="collapse-item {{ Request::is('progress*') ? 'active' : '' }}" href="{{ url('progress') }}">Progress Pekerjaan</a>
      </div>
    </div>
  </li>
-->
 <!-- Data Warehouse
<li class="nav-item {{ Request::is('materials*', 'materials_monitoring*', 'check_stock*', 'pengajuan_barang*', 'pengeluaran_barang*', 'pengembalian_barang*', 'kalkulasi_satuan*') ? 'active' : '' }} border-bottom">
    <a class="nav-link px-5 collapsed" href="#" data-toggle="collapse" data-target="#collapseWarehouse" aria-expanded="false" aria-controls="collapseWarehouse">
      <i class="fas fa-fw fa-warehouse"></i>
      <span>Data Warehouse</span>
    </a>
    <div id="collapseWarehouse" class="collapse {{ Request::is('materials*', 'materials_monitoring*', 'check_stock*', 'pengajuan_barang*', 'pengeluaran_barang*', 'pengembalian_barang*', 'kalkulasi_satuan*') ? 'show' : '' }}" aria-labelledby="headingPages" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header px-3">Rekapitulasi Gudang</h6>
        <a class="collapse-item {{ Request::is('dataproduk*') ? 'active' : '' }}" href="{{ url('dataproduk') }}">Data Material & Produk</a>
        <a class="collapse-item {{ Request::is('barangkeluar*') ? 'active' : '' }}" href="{{ url('barangkeluar') }}">Pengeluaran Barang</a>
        <a class="collapse-item {{ Request::is('pengembalian_barang*') ? 'active' : '' }}" href="{{ url('pengembalian_barang') }}">Pengembalian Barang</a>
        <a class="collapse-item {{ Request::is('kalkulasi_satuan*') ? 'active' : '' }}" href="{{ url('kalkulasi_satuan') }}">Kalkulasi Satuan</a>
      </div>
    </div>
  </li>
-->

  @elseif (Auth::user()->role == 'Staff1')
  <!-- Menu items for Staff1
  <li class="nav-item {{ Request::is('dashboard') ? 'active' : '' }} border-bottom">
    <a class="nav-link px-5 w-100" href="{{ url('dashboard') }}">
      <i class="fas fa-fw fa-chart-bar"></i>
      <span>Dashboard</span>
    </a>
  </li>

  <li class="nav-item {{ Request::is('spph*', 'spk*', 'item_pekerjaan*', 'progress*') ? 'active' : '' }} border-bottom">
    <a class="nav-link px-5 collapsed" href="#" data-toggle="collapse" data-target="#collapseMarketingStaff1" aria-expanded="true" aria-controls="collapseMarketingStaff1">
      <i class="fas fa-fw fa-calendar-alt"></i>
      <span>Data Marketing</span>
    </a>
    <div id="collapseMarketingStaff1" class="collapse {{ Request::is('spph*', 'spk*', 'item_pekerjaan*', 'progress*') ? 'show' : '' }}" aria-labelledby="headingPages" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header px-5">Rekapitulasi SPPH dan SPK</h6>
        <a class="collapse-item {{ Request::is('spph*') ? 'active' : '' }}" href="{{ url('spph') }}">Incoming SPPH</a>
        <a class="collapse-item {{ Request::is('spk*') ? 'active' : '' }}" href="{{ url('spk') }}">Outgoing SPK</a>
        <a class="collapse-item {{ Request::is('item_pekerjaan*') ? 'active' : '' }}" href="{{ url('item_pekerjaan') }}">Item Pekerjaan</a>
        <a class="collapse-item {{ Request::is('progress*') ? 'active' : '' }}" href="{{ url('progress') }}">Progress Pekerjaan</a>
      </div>
    </div>
  </li>
-->
  @endif

  <!-- Common menu items for all roles
  <li class="nav-item {{ Request::is('logout') ? 'active' : '' }} border-bottom">
    <a class="nav-link px-5 w-100" href="#" data-toggle="modal" data-target="#logoutModal">
      <i class="fas fa-sign-out-alt mx-1"></i>
      <span>Keluar</span>
    </a>
  </li>
</ul>

End of Sidebar


<script>
  document.addEventListener('DOMContentLoaded', function () {
    var sidebarLinks = document.querySelectorAll('.nav-link');
    var isCreatePage = currentUrl.includes('/create');

    sidebarLinks.forEach(function (link) {
      link.classList.add('disabled');
      link.setAttribute('aria-disabled', 'true');
      link.addEventListener('click', function (event) {
        event.preventDefault();
      });
    });
  });
</script>
-->
