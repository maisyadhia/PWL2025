<div class="sidebar">
  <!-- Sidebar user panel (fixed structure with dynamic content) -->
  <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
          @auth
          <img src="{{ auth()->user()->foto ? asset(auth()->user()->foto) : asset('dist/img/user2-160x160.jpg') }}" 
     class="img-circle elevation-2" alt="User Image">
          @else
          <img src="{{ asset('dist/img/user2-160x160.jpg') }}" 
               class="img-circle elevation-2" alt="User Image">
          @endauth
      </div>
      <div class="info">
          <a href="{{ route('profile.edit') }}" class="d-block">
              @auth
                  {{ auth()->user()->nama ?? 'Alexander Pierce' }}
              @else
                  Alexander Pierce
              @endauth
          </a>
      </div>
  </div>

  <!-- SidebarSearch Form -->
  <div class="form-inline mt-2">
      <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
              <button class="btn btn-sidebar">
                  <i class="fas fa-search fa-fw"></i>
              </button>
          </div>
      </div>
  </div>

  <!-- Sidebar Menu -->
  <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
              <a href="{{ url('/') }}" class="nav-link {{ ($activeMenu == 'dashboard')? 'active' : '' }} ">
                  <i class="nav-icon fas fa-tachometer-alt"></i>
                  <p>Dashboard</p>
              </a>
          </li>

          <!-- Menu Profil Saya -->
          <li class="nav-header">Akun</li>
          <li class="nav-item">
              <a href="{{ route('profile.edit') }}" class="nav-link {{ ($activeMenu == 'profile') ? 'active' : '' }}">
                  <i class="nav-icon fas fa-user-circle"></i>
                  <p>Profil Saya</p>
              </a>
          </li>

        <!-- Supplier Data Section -->
        <li class="nav-header">Data Supplier</li>
        <li class="nav-item">
          <a href="{{ route('supplier.index') }}" class="nav-link {{ ($activeMenu == 'supplier')? 'active' : '' }}">
            <i class="nav-icon fas fa-truck"></i>
            <p>Data Supplier</p>
          </a>
        </li>
        
        

        <li class="nav-header">Data Pengguna</li>
        <li class="nav-item">
          <a href="{{ url('/level') }}" class="nav-link {{ ($activeMenu == 'level')? 'active' : '' }} ">
            <i class="nav-icon fas fa-layer-group"></i>
            <p>Level User</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ url('/user') }}" class="nav-link {{ ($activeMenu == 'user')? 'active' : '' }}">
            <i class="nav-icon far fa-user"></i>
            <p>Data User</p>
          </a>
        </li>
        <li class="nav-header">Data Barang</li>
        <li class="nav-item">
          <a href="{{ url('/kategori') }}" class="nav-link {{ ($activeMenu == 'kategori')? 'active' : '' }} ">
            <i class="nav-icon far fa-bookmark"></i>
            <p>Kategori Barang</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ url('/barang') }}" class="nav-link {{ ($activeMenu == 'barang')? 'active' : '' }} ">
            <i class="nav-icon far fa-list-alt"></i>
            <p>Data Barang</p>
          </a>
        </li>
        <li class="nav-header">Data Transaksi</li>
        <li class="nav-item">
          <a href="{{ url('/stok') }}" class="nav-link {{ ($activeMenu == 'stok')? 'active' : '' }} ">
            <i class="nav-icon fas fa-cubes"></i>
            <p>Stok Barang</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ url('/barang') }}" class="nav-link {{ ($activeMenu == 'penjualan')? 'active' : '' }} ">
            <i class="nav-icon fas fa-cash-register"></i>
            <p>Transaksi Penjualan</p>
          </a>
        </li>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->

    <li class="nav-item">
      <form action="{{ route('logout') }}" method="POST" id="logout-form">
          @csrf
          <button type="submit" class="nav-link text-danger border-0 bg-transparent w-100 text-left" id="logout-btn">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <span>Logout</span>
          </button>
      </form>
  </li>
  
</div>

