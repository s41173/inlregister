<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
      <li class="nav-item">
        {{-- <router-link to="/dashboard" class="nav-link">
          <i class="nav-icon fas fa-tachometer-alt blue"></i>
          <p>
            Dashboard
          </p>
        </router-link>
      </li> --}}

      <li class="nav-item">
        <router-link to="/contractsearch" class="nav-link">
          <i class="nav-icon fas fa-tags green"></i>
          <p>Cari No Kontrak</p>
        </router-link>
      </li>
      <li class="nav-item">
        <router-link to="/reg" class="nav-link">
          <i class="nav-icon fas fa-tags green"></i>
          <p>Registrasi</p>
        </router-link>
      </li>    
      <li class="nav-item">
        <router-link to="/qcpass" class="nav-link">
          <i class="nav-icon fas fa-tags green"></i>
          <p>Qcpass</p>
        </router-link>
      </li>    
      


      @if (Session::get('login_level') == "ADMINWEB")
      <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
          <i class="nav-icon fas fa-cog green"></i>
          <p>
            Settings
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item">
            <router-link to="/userserp" class="nav-link">
              <i class="fa fa-users nav-icon blue"></i>
              <p>Users Login</p>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="/driver" class="nav-link">
              <i class="fa fa-users nav-icon blue"></i>
              <p>Driver Name</p>
            </router-link>
          </li>          
          <li class="nav-item">
            <router-link to="/segel" class="nav-link">
              <i class="nav-icon fas fa-list-ol green"></i>
              <p>
                Segel
              </p>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="/truck" class="nav-link">
              <i class="nav-icon fas fa-list-ol green"></i>
              <p>
                Truck
              </p>
            </router-link>
          </li>          
          {{-- <li class="nav-item">
            <router-link to="/products" class="nav-link">
              <i class="nav-icon fas fa-list orange"></i>
              <p>
                Product
              </p>
            </router-link>
          </li> --}}
          {{-- <li class="nav-item">
            <router-link to="/product/category" class="nav-link">
              <i class="nav-icon fas fa-list-ol green"></i>
              <p>
                Category
              </p>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="/product/tag" class="nav-link">
              <i class="nav-icon fas fa-tags green"></i>
              <p>
                Tags
              </p>
            </router-link>
          </li>
          
          <li class="nav-item">
            <router-link to="/developer" class="nav-link">
                <i class="nav-icon fas fa-cogs white"></i>
                <p>
                    Developer
                </p>
            </router-link>
          </li> --}}
        </ul>
      </li>

      @endif
      
      

      <li class="nav-item">
        <a href="#" class="nav-link" onclick="event.preventDefault();
          document.getElementById('logout-form').submit();">
          <i class="nav-icon fas fa-power-off red"></i>
          <p>
            {{ __('Logout') }}
          </p>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          @csrf
        </form>
      </li>
    </ul>
  </nav>