  <div class="main-sidebar sidebar-style-2">
      <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
              <a href="">Quản trị Admin</a>
          </div>
          <div class="sidebar-brand sidebar-brand-sm">
              <a href="">St</a>
          </div>
          <ul class="sidebar-menu">
              <li class="menu-header">Quản trị thống kê</li>
              <li class="dropdown">
                  <a href="#" class="nav-link has-dropdown"><i class="fas fa-fire"></i><span>Tổng quan</span></a>
                  <ul class="dropdown-menu">
                      <li><a class="nav-link" href="{{ route('admin.dashboard') }}">Chi tiết</a></li>
                  </ul>
              </li>
              <li class="menu-header">Quản Lý</li>
              <li class="dropdown">
                  <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-th"></i>
                      <span>Sản phẩm</span></a>
                  <ul class="dropdown-menu">
                      <li><a class="nav-link" href="{{ route('admin.products.index') }}">Danh sách sản phẩm</a></li>

                  </ul>
              </li>
              <li class="dropdown">
                  <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-th"></i>
                      <span>Người dùng</span></a>
                  <ul class="dropdown-menu">
                      <li><a class="nav-link" href="{{ route('admin.users.index') }}">Danh sách người dùng</a></li>

                  </ul>
              </li>
              <li class="dropdown">
                  <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-th"></i>
                      <span>Khách hàng</span></a>
                  <ul class="dropdown-menu">
                      <li><a class="nav-link" href="{{ route('admin.customers.index') }}">Danh sách khách hàng</a></li>

                  </ul>
              </li>

          </ul>

          <div class="mt-4 mb-4 p-3 hide-sidebar-mini">
              <a href="{{ route('admin.logouts') }}" class="btn btn-primary btn-lg btn-block btn-icon-split">
                  <i class="fas fa-rocket"></i> Đăng xuất
              </a>
          </div>
      </aside>
  </div>
