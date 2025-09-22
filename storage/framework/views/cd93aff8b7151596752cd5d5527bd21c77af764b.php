<?php
    $admin_logo = getSettingsValByName('company_logo');
    $ids = parentId();
    $authUser = \App\Models\User::find($ids);
    $subscription = \App\Models\Subscription::find($authUser->subscription);
    $routeName = \Request::route()->getName();
    $pricing_feature_settings = getSettingsValByIdName(1, 'pricing_feature');
?>
<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="#" class="b-brand text-primary">
                <!-- <img src="<?php echo e(asset(Storage::url('upload/logo/')) . '/' . (isset($admin_logo) && !empty($admin_logo) ? $admin_logo : 'logo.png')); ?>"
                    alt="" class="logo logo-lg" /> -->
                    <img src="../../public/assets/images/authentication/logo.png"
                    alt="" class="logo logo-lg" />
            </a>
        </div>
        <div class="navbar-content">
            <ul class="pc-navbar">
                <li class="pc-item pc-caption">
                    <label><?php echo e(__('Home')); ?></label>
                    <i class="ti ti-dashboard"></i>
                </li>
                <li class="pc-item <?php echo e(in_array($routeName, ['dashboard', 'home', '']) ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('dashboard')); ?>" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
                        <span class="pc-mtext"><?php echo e(__('Dashboard')); ?></span>
                    </a>
                </li>
                <?php if(\Auth::user()->type == 'super admin'): ?>
                    <?php if(Gate::check('manage user')): ?>
                        <li class="pc-item <?php echo e(in_array($routeName, ['users.index']) ? 'active' : ''); ?>">
                            <a href="<?php echo e(route('users.index')); ?>" class="pc-link">
                                <span class="pc-micon"><i class="ti ti-user-plus"></i></span>
                                <span class="pc-mtext"><?php echo e(__('Customers')); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php else: ?>
                    <?php if(Gate::check('manage user') || Gate::check('manage role') || Gate::check('manage logged history')): ?>
                        <!-- <li
                            class="pc-item pc-hasmenu <?php echo e(in_array($routeName, ['users.index', 'logged.history', 'role.index', 'role.create', 'role.edit']) ? 'pc-trigger active' : ''); ?>">
                            <a href="#!" class="pc-link">
                                <span class="pc-micon">
                                    <i class="ti ti-users"></i>
                                </span>
                                <span class="pc-mtext"><?php echo e(__('Staff Management')); ?></span>
                                <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                            </a>
                            <ul class="pc-submenu"
                                style="display: <?php echo e(in_array($routeName, ['users.index', 'logged.history', 'role.index', 'role.create', 'role.edit']) ? 'block' : 'none'); ?>">
                                <?php if(Gate::check('manage user')): ?>
                                    <li class="pc-item <?php echo e(in_array($routeName, ['users.index']) ? 'active' : ''); ?>">
                                        <a class="pc-link" href="<?php echo e(route('users.index')); ?>"><?php echo e(__('Users')); ?></a>
                                    </li>
                                <?php endif; ?>
                                <?php if(Gate::check('manage role')): ?>
                                    <li
                                        class="pc-item  <?php echo e(in_array($routeName, ['role.index', 'role.create', 'role.edit']) ? 'active' : ''); ?>">
                                        <a class="pc-link" href="<?php echo e(route('role.index')); ?>"><?php echo e(__('Roles')); ?> </a>
                                    </li>
                                <?php endif; ?>
                                <?php if($pricing_feature_settings == 'off' || $subscription->enabled_logged_history == 1): ?>
                                    <?php if(Gate::check('manage logged history')): ?>
                                        <li
                                            class="pc-item  <?php echo e(in_array($routeName, ['logged.history']) ? 'active' : ''); ?>">
                                            <a class="pc-link"
                                                href="<?php echo e(route('logged.history')); ?>"><?php echo e(__('Logged History')); ?></a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </ul>
                        </li> -->
                    <?php endif; ?>
                <?php endif; ?>


                <?php if(Gate::check('manage property') ||
                        Gate::check('manage unit') ||
                        Gate::check('manage tenant') ||
                        Gate::check('manage invoice') ||
                        Gate::check('manage expense') ||
                        Gate::check('manage maintainer') ||
                        Gate::check('manage maintenance request') ||
                        Gate::check('manage contact') ||
                        Gate::check('manage support') ||
                        Gate::check('manage note')): ?>
                    <!-- <li class="pc-item pc-caption">
                        <label><?php echo e(__('Business Management')); ?></label>
                        <i class="ti ti-chart-arcs"></i>
                    </li> -->

                    <?php if(Gate::check('manage tenant')): ?>
                        <li
                            class="pc-item <?php echo e(in_array($routeName, ['tenant.index', 'tenant.create', 'tenant.edit', 'tenant.show']) ? 'active' : ''); ?>">
                            <a href="<?php echo e(route('tenant.index')); ?>" class="pc-link">
                                <span class="pc-micon"><i class="ti ti-user"></i></span>
                                <span class="pc-mtext"><?php echo e(__('Tenants Management')); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if(Gate::check('manage maintainer')): ?>
                        
                    <?php endif; ?>
                    <?php if(Gate::check('manage tenant') || Gate::check('manage property') || Gate::check('manage unit')): ?>
                        <li
                            class="pc-item pc-hasmenu  <?php echo e(in_array($routeName, ['property.index', 'property.create', 'property.edit', 'property.show', 'unit.index']) ? 'pc-trigger active' : ''); ?>">
                            <a href="#!" class="pc-link">
                                <span class="pc-micon">
                                    <i class="ti ti-home"></i>
                                </span>
                                <span class="pc-mtext"><?php echo e(__('Property Management')); ?></span>
                                <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                            </a>
                            <ul class="pc-submenu"
                                style="display: <?php echo e(in_array($routeName, ['property.index', 'property.create', 'property.edit', 'property.show', 'unit.index']) ? 'block' : 'none'); ?>">
                                <?php if(Gate::check('manage property')): ?>
                                    <li
                                        class="pc-item <?php echo e(in_array($routeName, ['property.index', 'property.create', 'property.edit', 'property.show']) ? 'active' : ''); ?>">
                                        <a class="pc-link"
                                            href="<?php echo e(route('property.index')); ?>"><?php echo e(__('Properties')); ?></a>
                                    </li>
                                <?php endif; ?>
                                <?php if(Gate::check('manage unit')): ?>
                                    <li class="pc-item <?php echo e(in_array($routeName, ['unit.index']) ? 'active' : ''); ?>">
                                        <a class="pc-link" href="<?php echo e(route('unit.index')); ?>"><?php echo e(__('Units')); ?></a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </li>

                    <?php endif; ?>

                    <?php if(Gate::check('manage maintainer') || Gate::check('manage maintenance request')): ?>
                        <!-- <li
                            class="pc-item pc-hasmenu  <?php echo e(in_array($routeName, ['maintenance-request.index', 'maintenance-request.pending', 'maintenance-request.inprogress']) ? 'pc-trigger active' : ''); ?>">
                            <a href="#!" class="pc-link">
                                <span class="pc-micon">
                                    <i class="ti ti-tool"></i>
                                </span>
                                <span class="pc-mtext"><?php echo e(__('Maintenance')); ?></span>
                                <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                            </a>
                            <ul class="pc-submenu"
                                style="display: <?php echo e(in_array($routeName, ['maintenance-request.index', 'maintenance-request.pending', 'maintenance-request.inprogress']) ? 'block' : 'none'); ?>">
                                <?php if(Gate::check('manage maintenance request')): ?>
                                    <li
                                        class="pc-item <?php echo e(in_array($routeName, ['maintenance-request.index']) ? 'active' : ''); ?>">
                                        <a class="pc-link"
                                            href="<?php echo e(route('maintenance-request.index')); ?>"><?php echo e(__('All Requests')); ?></a>
                                    </li>
                                    <li
                                        class="pc-item <?php echo e(in_array($routeName, ['maintenance-request.pending']) ? 'active' : ''); ?>">
                                        <a class="pc-link"
                                            href="<?php echo e(route('maintenance-request.pending')); ?>"><?php echo e(__('Pending')); ?></a>
                                    </li>
                                    <li
                                        class="pc-item <?php echo e(in_array($routeName, ['maintenance-request.inprogress']) ? 'active' : ''); ?>">
                                        <a class="pc-link"
                                            href="<?php echo e(route('maintenance-request.inprogress')); ?>"><?php echo e(__('In Progress')); ?></a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </li> -->

                    <?php endif; ?>


                   

                    <!-- <?php if(Gate::check('manage invoice') || Gate::check('manage expense')): ?>
                        <li
                            class="pc-item pc-hasmenu  <?php echo e(in_array($routeName, ['invoice.index', 'invoice.create', 'invoice.edit', 'invoice.show', 'expense.index']) ? 'pc-trigger  active' : ''); ?>">
                            <a href="#!" class="pc-link">
                                <span class="pc-micon">
                                    <i class="ti ti-file-invoice"></i>
                                </span>
                                <span class="pc-mtext"><?php echo e(__('Finance')); ?></span>
                                <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                            </a>
                            <ul class="pc-submenu"
                                style="display: <?php echo e(in_array($routeName, ['invoice.index', 'invoice.create', 'invoice.edit', 'invoice.show', 'expense.index']) ? 'block' : 'none'); ?>">
                                <?php if(Gate::check('manage invoice')): ?>
                                    <li
                                        class="pc-item <?php echo e(in_array($routeName, ['invoice.index', 'invoice.create', 'invoice.edit', 'invoice.show']) ? 'active' : ''); ?>">
                                        <a class="pc-link"
                                            href="<?php echo e(route('invoice.index')); ?>"><?php echo e(__('Invoices')); ?></a>
                                    </li>
                                <?php endif; ?>
                                <?php if(Gate::check('manage expense')): ?>
                                     <li class="pc-item <?php echo e(in_array($routeName, ['expense.index']) ? 'active' : ''); ?>">
                                        <a class="pc-link"
                                            href="<?php echo e(route('expense.index')); ?>"><?php echo e(__('Expense')); ?></a>
                                    </li> 
                                <?php endif; ?>
                            </ul>
                        </li>

                    <?php endif; ?> -->





                    <!-- <?php if(Gate::check('manage contact')): ?>
                        <li class="pc-item <?php echo e(in_array($routeName, ['contact.index']) ? 'active' : ''); ?>">
                            <a href="<?php echo e(route('contact.index')); ?>" class="pc-link">
                                <span class="pc-micon"><i class="ti ti-phone-call"></i></span>
                                <span class="pc-mtext"><?php echo e(__('Contract Management')); ?></span>
                            </a>
                        </li>
                    <?php endif; ?> -->
                    <!-- <?php if(Gate::check('manage note')): ?>
                        <li class="pc-item <?php echo e(in_array($routeName, ['note.index']) ? 'active' : ''); ?> ">
                            <a href="<?php echo e(route('note.index')); ?>" class="pc-link">
                                <span class="pc-micon"><i class="ti ti-notebook"></i></span>
                                <span class="pc-mtext"><?php echo e(__('Exit Notices')); ?></span>
                            </a>
                        </li>
                    <?php endif; ?> -->


                   

                <?php endif; ?>
                <?php if(Auth::check() && strtolower(Auth::user()->type) === 'owner'): ?>
                <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link">
                            <span class="pc-micon">
                                <i class="ti ti-home"></i>
                            </span>
                            <span class="pc-mtext"><?php echo e(__('Invoices')); ?></span>
                            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                        </a>
                        <ul class="pc-submenu" style="display: <?php echo e(request()->routeIs('property.*') || request()->routeIs('unit.*') ? 'block' : 'none'); ?>">
                            
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage property')): ?> 
                                <li class="pc-item <?php echo e(request()->routeIs('property.*') ? 'active' : ''); ?>">
                                    <a class="pc-link" href="<?php echo e(route('property.selectProperty')); ?>"><?php echo e(__('Utilities Invoices')); ?></a>
                                </li>
                                <li class="pc-item">
                                    <a class="pc-link" href="<?php echo e(url('late-fees')); ?>"><?php echo e(__('Late Fee')); ?></a>
                                </li>
                                <li class="pc-item">
                                    <a class="pc-link" href="<?php echo e(url('other')); ?>"><?php echo e(__('Other')); ?></a>
                                </li>
                                <!-- <li class="pc-item <?php echo e(request()->routeIs('property.*') ? 'active' : ''); ?>">
                                    <a class="pc-link" href="<?php echo e(route('property.selectProperty')); ?>"><?php echo e(__('Select Property')); ?></a>
                                </li> -->
                            <?php endif; ?>

                            <!-- <li class="pc-item">
                                    <a class="pc-link" href="#"><?php echo e(__('Other Invoices')); ?></a>
                                </li> -->

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage unit')): ?>
                                <!-- <li class="pc-item <?php echo e(request()->routeIs('unit.*') ? 'active' : ''); ?>">
                                    <a class="pc-link" href="<?php echo e(route('unit.index')); ?>"><?php echo e(__('b')); ?></a>
                                </li> -->
                            <?php endif; ?>
                            
                        </ul>
                    </li>
                    <li class="pc-item">
                        <a href="<?php echo e(url('payments')); ?>" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-ticket"></i></span>
                            <span class="pc-mtext"><?php echo e(__('Payments & Account Summary')); ?></span>
                        </a>
                    </li>
                <?php endif; ?>


                <?php if(Auth::check() && Auth::user()->type === 'tenant'): ?>
                    <li class="pc-item">
                        <a href="<?php echo e(url('tenant-profile')); ?>" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-user"></i></span>
                            <span class="pc-mtext"><?php echo e(__('Tenant Profile')); ?></span>
                        </a>
                    </li>
                    <li class="pc-item">
                        <a href="<?php echo e(url('property-details')); ?>" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-home"></i></span>
                            <span class="pc-mtext"><?php echo e(__('Property Details')); ?></span>
                        </a>
                    </li>
                    <li class="pc-item">
                        <a href="<?php echo e(url('payment-section')); ?>" class="pc-link">
                            <span class="pc-micon"><i class="bi bi-wallet2"></i></span>
                            <span class="pc-mtext"><?php echo e(__('Payment Section')); ?></span>
                        </a>
                    </li>
                    <li class="pc-item">
                        <a href="<?php echo e(url('tenant-ticket-support')); ?>" class="pc-link">
                            <span class="pc-micon"><i class="bi bi-ticket"></i></span>
                            <span class="pc-mtext"><?php echo e(__('Ticket Support & Help')); ?></span>
                        </a>
                    </li>
                    <li class="pc-item">
                        <a href="<?php echo e(url('tenant-notices')); ?>" class="pc-link">
                            <span class="pc-micon"><i class="bi bi-file-earmark-text"></i></span>
                            <span class="pc-mtext"><?php echo e(__('Notices')); ?></span>
                        </a>
                    </li>
                    <li class="pc-item">
                        <a href="<?php echo e(url('tenant-documents')); ?>" class="pc-link">
                            <span class="pc-micon"><i class="bi bi-file-earmark-break"></i></span>
                            <span class="pc-mtext"><?php echo e(__('Documents')); ?></span>
                        </a>
                    </li>
                    <!-- <li class="pc-item">
                        <a href="<?php echo e(url('utilities-invoices')); ?>" class="pc-link">
                            <span class="pc-micon"><i class="bi bi-receipt"></i></span>
                            <span class="pc-mtext"><?php echo e(__('Utilities Invoices')); ?></span>
                        </a>
                    </li> -->
                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-home"></i></span>
                            <span class="pc-mtext"><?php echo e(__('Invoices')); ?></span>
                            <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                        </a>
                        <ul class="pc-submenu">
                            <li class="pc-item">
                                <a class="pc-link" href="<?php echo e(url('utilities-invoices')); ?>"><?php echo e(__('Utilities Invoices')); ?></a>
                            </li>
                            <li class="pc-item">
                                <a class="pc-link" href="<?php echo e(url('tenant-late-fees')); ?>"><?php echo e(__('Late Fee')); ?></a>
                            </li>
                            <li class="pc-item">
                                <a class="pc-link" href="<?php echo e(url('tenant-other-invoice')); ?>"><?php echo e(__('Other')); ?></a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>


                <?php if(Gate::check('manage notification')): ?>
                    <!-- <li class="pc-item pc-caption">
                        <label><?php echo e(__('System Configuration')); ?></label>
                        <i class="ti ti-chart-arcs"></i>
                    </li>
                    <?php if(Gate::check('manage types')): ?>
                        <li class="pc-item <?php echo e(in_array($routeName, ['type.index']) ? 'active' : ''); ?>">
                            <a href="<?php echo e(route('type.index')); ?>" class="pc-link">
                                <span class="pc-micon"><i data-feather="file-text"></i></span>
                                <span class="pc-mtext"><?php echo e(__('Types')); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if(Gate::check('manage notification')): ?>
                        <li class="pc-item <?php echo e(in_array($routeName, ['notification.index']) ? 'active' : ''); ?> ">
                            <a href="<?php echo e(route('notification.index')); ?>" class="pc-link">
                                <span class="pc-micon"><i class="ti ti-bell"></i></span>
                                <span class="pc-mtext"><?php echo e(__('Email Notification')); ?></span>
                            </a>
                        </li> -->
                    <?php endif; ?>

                <?php endif; ?>


                <?php if(Gate::check('manage pricing packages') ||
                        Gate::check('manage pricing transation') ||
                        Gate::check('manage account settings') ||
                        Gate::check('manage password settings') ||
                        Gate::check('manage general settings') ||
                        Gate::check('manage email settings') ||
                        Gate::check('manage payment settings') ||
                        Gate::check('manage company settings') ||
                        Gate::check('manage seo settings') ||
                        Gate::check('manage google recaptcha settings')): ?>
                    <li class="pc-item pc-caption">
                        <label><?php echo e(__('System Settings')); ?></label>
                        <i class="ti ti-chart-arcs"></i>
                    </li>

                    <?php if(Gate::check('manage FAQ') || Gate::check('manage Page')): ?>
                        <li
                            class="pc-item pc-hasmenu <?php echo e(in_array($routeName, ['homepage.index', 'FAQ.index', 'pages.index', 'footerSetting']) ? 'active' : ''); ?>">
                            <a href="#!" class="pc-link">
                                <span class="pc-micon">
                                    <i class="ti ti-layout-rows"></i>
                                </span>
                                <span class="pc-mtext"><?php echo e(__('CMS')); ?></span>
                                <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                            </a>
                            <ul class="pc-submenu"
                                style="display: <?php echo e(in_array($routeName, ['homepage.index', 'FAQ.index', 'pages.index', 'footerSetting']) ? 'block' : 'none'); ?>">
                                <?php if(Gate::check('manage home page')): ?>
                                    <li
                                        class="pc-item <?php echo e(in_array($routeName, ['homepage.index']) ? 'active' : ''); ?> ">
                                        <a href="<?php echo e(route('homepage.index')); ?>"
                                            class="pc-link"><?php echo e(__('Home Page')); ?></a>
                                    </li>
                                <?php endif; ?>
                                <?php if(Gate::check('manage Page')): ?>
                                    <li class="pc-item <?php echo e(in_array($routeName, ['pages.index']) ? 'active' : ''); ?> ">
                                        <a href="<?php echo e(route('pages.index')); ?>"
                                            class="pc-link"><?php echo e(__('Custom Page')); ?></a>
                                    </li>
                                <?php endif; ?>
                                <?php if(Gate::check('manage FAQ')): ?>
                                    <li class="pc-item <?php echo e(in_array($routeName, ['FAQ.index']) ? 'active' : ''); ?> ">
                                        <a href="<?php echo e(route('FAQ.index')); ?>" class="pc-link"><?php echo e(__('FAQ')); ?></a>
                                    </li>
                                <?php endif; ?>
                                <?php if(Gate::check('manage footer')): ?>
                                    <li
                                        class="pc-item <?php echo e(in_array($routeName, ['footerSetting']) ? 'active' : ''); ?> ">
                                        <a href="<?php echo e(route('footerSetting')); ?>"
                                            class="pc-link"><?php echo e(__('Footer')); ?></a>
                                    </li>
                                <?php endif; ?>
                                <?php if(Gate::check('manage auth page')): ?>
                                    <li
                                        class="pc-item <?php echo e(in_array($routeName, ['authPage.index']) ? 'active' : ''); ?> ">
                                        <a href="<?php echo e(route('authPage.index')); ?>"
                                            class="pc-link"><?php echo e(__('Auth Page')); ?></a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                    <?php if(Auth::user()->type == 'super admin' || $pricing_feature_settings == 'on'): ?>
                        <?php if(Gate::check('manage pricing packages') || Gate::check('manage pricing transation')): ?>
                            <li
                                class="pc-item pc-hasmenu <?php echo e(in_array($routeName, ['subscriptions.index', 'subscriptions.show', 'subscription.transaction']) ? 'active' : ''); ?>">
                                <a href="#!" class="pc-link">
                                    <span class="pc-micon">
                                        <i class="ti ti-package"></i>
                                    </span>
                                    <span class="pc-mtext"><?php echo e(__('Pricing')); ?></span>
                                    <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                                </a>
                                <ul class="pc-submenu"
                                    style="display: <?php echo e(in_array($routeName, ['subscriptions.index', 'subscriptions.show', 'subscription.transaction']) ? 'block' : 'none'); ?>">
                                    <?php if(Gate::check('manage pricing packages')): ?>
                                        <li
                                            class="pc-item <?php echo e(in_array($routeName, ['subscriptions.index', 'subscriptions.show']) ? 'active' : ''); ?>">
                                            <a class="pc-link"
                                                href="<?php echo e(route('subscriptions.index')); ?>"><?php echo e(__('Packages')); ?></a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if(Gate::check('manage pricing transation')): ?>
                                        <li
                                            class="pc-item <?php echo e(in_array($routeName, ['subscription.transaction']) ? 'active' : ''); ?>">
                                            <a class="pc-link"
                                                href="<?php echo e(route('subscription.transaction')); ?>"><?php echo e(__('Transactions')); ?></a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if(Gate::check('manage coupon') || Gate::check('manage coupon history')): ?>
                        <li
                            class="pc-item pc-hasmenu <?php echo e(in_array($routeName, ['coupons.index', 'coupons.history']) ? 'active' : ''); ?>">
                            <a href="#!" class="pc-link">
                                <span class="pc-micon">
                                    <i class="ti ti-shopping-cart-discount"></i>
                                </span>
                                <span class="pc-mtext"><?php echo e(__('Coupons')); ?></span>
                                <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                            </a>
                            <ul class="pc-submenu"
                                style="display: <?php echo e(in_array($routeName, ['coupons.index', 'coupons.history']) ? 'block' : 'none'); ?>">
                                <?php if(Gate::check('manage coupon')): ?>
                                    <li
                                        class="pc-item <?php echo e(in_array($routeName, ['coupons.index']) ? 'active' : ''); ?>">
                                        <a class="pc-link"
                                            href="<?php echo e(route('coupons.index')); ?>"><?php echo e(__('All Coupon')); ?></a>
                                    </li>
                                <?php endif; ?>
                                <?php if(Gate::check('manage coupon history')): ?>
                                    <li
                                        class="pc-item <?php echo e(in_array($routeName, ['coupons.history']) ? 'active' : ''); ?>">
                                        <a class="pc-link"
                                            href="<?php echo e(route('coupons.history')); ?>"><?php echo e(__('Coupon History')); ?></a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>

                    <?php if(Gate::check('manage account settings') ||
                            Gate::check('manage password settings') ||
                            Gate::check('manage general settings') ||
                            Gate::check('manage email settings') ||
                            Gate::check('manage payment settings') ||
                            Gate::check('manage company settings') ||
                            Gate::check('manage seo settings') ||
                            Gate::check('manage google recaptcha settings')): ?>
                        <li class="pc-item <?php echo e(in_array($routeName, ['setting.index']) ? 'active' : ''); ?> ">
                            <a href="<?php echo e(route('setting.index')); ?>" class="pc-link">
                                <span class="pc-micon"><i class="ti ti-settings"></i></span>
                                <span class="pc-mtext"><?php echo e(__('Settings')); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>

                        
                        <li class="pc-item ">
                            <a href="<?php echo e(url('ticket-support')); ?>" class="pc-link">
                                <span class="pc-micon"><i class="ti ti-layout"></i></span>
                                <span class="pc-mtext"><?php echo e(__('Ticket & Support')); ?></span>
                            </a>
                        </li>
                        <li class="pc-item ">
                            <a href="<?php echo e(url('manage-notice')); ?>" class="pc-link">
                                <span class="pc-micon"><i class="ti ti-layout"></i></span>
                                <span class="pc-mtext"><?php echo e(__('Manage Notice')); ?></span>
                            </a>
                        </li>
                        <li class="pc-item ">
                            <a href="<?php echo e(url('manage-template')); ?>" class="pc-link">
                                <span class="pc-micon"><i class="ti ti-layout"></i></span>
                                <span class="pc-mtext"><?php echo e(__('Manage Template')); ?></span>
                            </a>
                        </li>
                        

                <?php endif; ?>
            </ul>
            <div class="w-100 text-center">
                <div class="badge theme-version badge rounded-pill bg-light text-dark f-12"></div>
            </div>
        </div>
    </div>
</nav>
<?php /**PATH /home/u527856812/domains/dodgerblue-lapwing-476569.hostingersite.com/public_html/resources/views/admin/menu.blade.php ENDPATH**/ ?>