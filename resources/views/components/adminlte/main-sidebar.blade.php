<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('admin.index') }}" class="brand-link">
        <img src="{{ asset('images/logo_unpi.png') }}" alt="AdminLTE Logo"
            class="brand-image img-circle" style="opacity: .8">
        <span class="brand-text font-weight-light">UNPI</span>
    </a>

    <div class="sidebar">
        <div class="user-panel d-flex mt-3 mb-3 pb-3">
            <div class="image">
                <img src="{{ Auth::user()->image ? asset('storage/' . Auth::user()->image) : asset('images/profile.png') }}"
                    class="img-circle" alt="User Image">
            </div>
            <div class="info">
                <a href="{{ route('account.index') }}" class="d-block">{{ Auth::user()->name }}</a>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false" style="overflow-x: hidden;">
                <li class="nav-item">
                    <a href="{{ route('admin.index') }}"
                        class="nav-link {{ request()->routeIs('admin.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-home"></i>
                        <p>
                            Home
                            {{-- <span class="right badge badge-danger">New</span> --}}
                        </p>
                    </a>
                </li>
                @canany(['blogs-read', 'kpi-read'])
                    <li class="nav-header text-uppercase">Content Control</li>

                    @if (Route::has('admin.blogs.index'))
                        @can('blogs-read')
                            <li class="nav-item">
                                <a href="{{ route('admin.blogs.index') }}"
                                    class="nav-link {{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-newspaper"></i>
                                    <p>
                                        Blogs
                                        {{-- <span class="right badge badge-danger">New</span> --}}
                                    </p>
                                </a>
                            </li>
                        @endcan
                    @endif
                    @can('kpi-read')
                        <li class="nav-item">
                            <a href="{{ route('admin.kpi.index') }}"
                                class="nav-link {{ request()->routeIs('admin.kpi.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-stream"></i>
                                <p>
                                    Periode KPI
                                    {{-- <span class="right badge badge-danger">New</span> --}}
                                </p>
                            </a>
                        </li>
                    @endcan
                    @can('achievements-read')
                        <li class="nav-item">
                            <a href="{{ route('admin.achievements.index') }}"
                                class="nav-link {{ request()->routeIs('admin.achievements.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-award"></i>
                                <p>
                                    Pencapaian
                                    {{-- <span class="right badge badge-danger">New</span> --}}
                                </p>
                            </a>
                        </li>
                    @endcan
                    @can('presence-scopes-read')
                        <li class="nav-item">
                            <a href="{{ route('admin.presence-scopes.index') }}"
                                class="nav-link {{ request()->routeIs('admin.presence-scopes.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-bullseye"></i>
                                <p>
                                    Lingkup Absensi
                                    {{-- <span class="right badge badge-danger">New</span> --}}
                                </p>
                            </a>
                        </li>
                    @endcan
                    @can('employees-presence-quota-read')
                        <li class="nav-item">
                            <a href="{{ route('admin.employees-presence-quota.index') }}"
                                class="nav-link {{ request()->routeIs('admin.employees-presence-quota.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-business-time"></i>
                                <p>
                                    Quota Absensi Karyawan
                                    {{-- <span class="right badge badge-danger">New</span> --}}
                                </p>
                            </a>
                        </li>
                    @endcan
                    @can('feedback-questions-read')
                        <li class="nav-item">
                            <a href="{{ route('admin.questions.index') }}"
                                class="nav-link {{ request()->routeIs('admin.questions.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-question"></i>
                                <p>
                                    Pertanyaan Umpan Balik
                                    {{-- <span class="right badge badge-danger">New</span> --}}
                                </p>
                            </a>
                        </li>
                    @endcan
                    @can('feedbacks-read')
                        <li class="nav-item">
                            <a href="{{ route('admin.feedbacks.index') }}"
                                class="nav-link {{ request()->routeIs('admin.feedbacks.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-comments"></i>
                                <p>
                                    Hasil Umpan Balik
                                    {{-- <span class="right badge badge-danger">New</span> --}}
                                </p>
                            </a>
                        </li>
                    @endcan
                    {{-- @can('semesters-read')
                        <li class="nav-item">
                            <a href="{{ route('admin.semesters.index') }}"
                                class="nav-link {{ request()->routeIs('admin.semesters.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-newspaper"></i>
                                <p>
                                    Semester     </p>
                            </a>
                        </li>
                    @endcan --}}
                    @can('majors-read')
                        <li class="nav-item">
                            <a href="{{ route('admin.majors.index') }}"
                                class="nav-link {{ request()->routeIs('admin.majors.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-university"></i>
                                <p>
                                    Jurusan
                                    {{-- <span class="right badge badge-danger">New</span> --}}
                                </p>
                            </a>
                        </li>
                    @endcan
                    @can('courses-read')
                        <li class="nav-item">
                            <a href="{{ route('admin.courses.index') }}"
                                class="nav-link {{ request()->routeIs('admin.courses.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-book"></i>
                                <p>
                                    Mata Kuliah
                                    {{-- <span class="right badge badge-danger">New</span> --}}
                                </p>
                            </a>
                        </li>
                    @endcan
                    @can('tendik-positions-read')
                        <li class="nav-item">
                            <a href="{{ route('admin.tendik-positions.index') }}"
                                class="nav-link {{ request()->routeIs('admin.tendik-positions.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-id-badge"></i>
                                <p>
                                    Kategori Tendik
                                    {{-- <span class="right badge badge-danger">New</span> --}}
                                </p>
                            </a>
                        </li>
                    @endcan
                    @can('employees-read')
                        <li class="nav-item">
                            <a href="{{ route('admin.employees.index') }}"
                                class="nav-link {{ request()->routeIs('admin.employees.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user-tie"></i>
                                <p>
                                    Daftar Karyawan & Dosen
                                    {{-- <span class="right badge badge-danger">New</span> --}}
                                </p>
                            </a>
                        </li>
                    @endcan
                    @can('students-read')
                        <li class="nav-item">
                            <a href="{{ route('admin.students.index') }}"
                                class="nav-link {{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user-graduate"></i>
                                <p>
                                    Daftar Mahasiswa
                                    {{-- <span class="right badge badge-danger">New</span> --}}
                                </p>
                            </a>
                        </li>
                    @endcan
                @endcanany
                <x-adminlte.sidebar-system />
                <li class="nav-item mt-2 pt-2" style="border-top: 1px solid #4f5962;">
                    <a href="{{ route('logout') }}" class="nav-link"
                        onclick="event.preventDefault();document.querySelector('#logoutForm').submit()">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>
                            Logout
                            {{-- <span class="right badge badge-danger">New</span> --}}
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <small class="d-block text-capitalize text-center" style="color: #c2c7d0;">{{ config('app.version') }}</small>
                </li>
            </ul>
        </nav>
    </div>
</aside>
