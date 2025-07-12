

<?php
    //$kt_logo_image = 'ktc.jpg';
    $kt_logo_image = 'ktc.jpg';
?>

<?php if(config('layout.brand.self.theme') === 'light'): ?>
    <?php
        //$kt_logo_image = 'ktc.png';
        $kt_logo_image = 'ktc.png';
    ?>
<?php elseif(config('layout.brand.self.theme') === 'dark'): ?>
    <?php
        //$kt_logo_image = 'ktc.png';
        $kt_logo_image = 'ktc.png';
    ?>
<?php endif; ?>

<div class="aside aside-left <?php echo e(Metronic::printClasses('aside', false)); ?> d-flex flex-column flex-row-auto"
    id="kt_aside">

    
    <div class="brand flex-column-auto <?php echo e(Metronic::printClasses('brand', false)); ?>" id="kt_brand">
        <div class="brand-logo text-center">
            <a href="<?php echo e(url('admin/dashboard')); ?>" style="width: 66%">
                <h2 class="brand-short" style="color:#f7cd46;">KTC</h2>
                <h2 class="brand-full" style="color:#f7cd46; display: none;">Krishna Trading Co.</h2>
            </a>
        </div>
    
        <?php if(config('layout.aside.self.minimize.toggle')): ?>
            <button class="brand-toggle btn btn-sm px-0" id="kt_aside_toggle">
                <?php echo e(Metronic::getSVG('media/svg/icons/Navigation/Angle-double-left.svg', 'svg-icon-xl')); ?>

            </button>
        <?php endif; ?>
    </div>
    

    
    <div class="aside-menu-wrapper flex-column-fluid" id="kt_aside_menu_wrapper">

        <?php if(config('layout.aside.self.display') === false): ?>
            <div class="header-logo">
                <a href="<?php echo e(url('/')); ?>">
                    <!-- <div></div> -->

                    <img alt="<?php echo e(config('app.name')); ?>" src="" />
                </a>
            </div>
        <?php endif; ?>

        <div id="kt_aside_menu" class="aside-menu my-4 <?php echo e(Metronic::printClasses('aside_menu', false)); ?>"
            data-menu-vertical="1" <?php echo e(Metronic::printAttrs('aside_menu')); ?>>
            <ul class="menu-nav <?php echo e(Metronic::printClasses('aside_menu_nav', false)); ?>">
                <li class="menu-item menu-item-submenu <?php echo $__env->yieldContent('dashboardmaster'); ?>" aria-haspopup="true" data-menu-toggle="hover">
                    <a href="<?php echo e(url('/')); ?>/admin/dashboard" class="menu-link menu-toggle">
                        <span class="svg-icon menu-icon">
                            <span class="flaticon-dashboard"></span>
                        </span>
                        <span class="menu-text">Dashboard</span>
                    </a>
                </li>

                <li class="menu-item menu-item-submenu <?php echo $__env->yieldContent('product'); ?>" aria-haspopup="true" data-menu-toggle="hover">
                    <a href="<?php echo e(url('/')); ?>/admin/product/list" class="menu-link menu-toggle">
                        <span class="svg-icon menu-icon">
                            🌿
                        </span>
                        <span class="menu-text">Product</span>
                    </a>
                </li>

                <li class="menu-item menu-item-submenu <?php echo $__env->yieldContent('warehouse'); ?>" aria-haspopup="true" data-menu-toggle="hover">
                    <a href="<?php echo e(url('/')); ?>/admin/warehouse/list" class="menu-link menu-toggle">
                        <span class="svg-icon menu-icon">
                            🏭
                        </span>
                        <span class="menu-text">Warehouse</span>
                    </a>
                </li>

                <li class="menu-item menu-item-submenu <?php echo $__env->yieldContent('userlist'); ?> <?php echo $__env->yieldContent('userrole'); ?>" aria-haspopup="true"
                    data-menu-toggle="hover">
                    <a href="#" class="menu-link menu-toggle">
                        <span class="svg-icon menu-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <polygon points="0 0 24 0 24 24 0 24" />
                                    <path
                                        d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z"
                                        fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                    <path
                                        d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z"
                                        fill="#000000" fill-rule="nonzero" />
                                </g>
                            </svg>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-text">Admin Users</span><i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu " kt-hidden-height="320" style=""><span
                            class="menu-arrow"></span>
                        <ul class="menu-subnav">
                            <li class="menu-item  menu-item-parent" aria-haspopup="true"><span
                                    class="menu-link"><span class="menu-text">Admin</span></span></li>
                            <li class="menu-item  <?php echo $__env->yieldContent('userrole'); ?>" aria-haspopup="true" data-menu-toggle="hover">
                                <a href="<?php echo e(url('/admin/roles/list')); ?>" class="menu-link menu-toggle">
                                    <i class="menu-bullet menu-bullet-line"><span></span></i>
                                    <span class="menu-text">Roles</span>
                                </a>
                            </li>
                            <li class="menu-item  <?php echo $__env->yieldContent('userlist'); ?>" aria-haspopup="true" data-menu-toggle="hover">
                                <a href="<?php echo e(url('/admin/users/list')); ?>" class="menu-link menu-toggle">
                                    <i class="menu-bullet menu-bullet-line"><span></span></i>
                                    <span class="menu-text">Users</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="menu-item menu-item-submenu  <?php echo $__env->yieldContent('settings'); ?>" aria-haspopup="true"
                    data-menu-toggle="hover">
                    <a href="<?php echo e(url('/admin/settings')); ?>" class="menu-link menu-toggle">
                        <span class="svg-icon menu-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24" />
                                    <path
                                        d="M5,8.6862915 L5,5 L8.6862915,5 L11.5857864,2.10050506 L14.4852814,5 L19,5 L19,9.51471863 L21.4852814,12 L19,14.4852814 L19,19 L14.4852814,19 L11.5857864,21.8994949 L8.6862915,19 L5,19 L5,15.3137085 L1.6862915,12 L5,8.6862915 Z M12,15 C13.6568542,15 15,13.6568542 15,12 C15,10.3431458 13.6568542,9 12,9 C10.3431458,9 9,10.3431458 9,12 C9,13.6568542 10.3431458,15 12,15 Z"
                                        fill="#000000" />
                                </g>
                            </svg>
                        </span>
                        <span class="menu-text">Settings</span>
                    </a>
                </li>

            </ul>
        </div>
    </div>

</div>
<?php /**PATH C:\wamp64\www\ktc\resources\views/admin/layout/base/_aside.blade.php ENDPATH**/ ?>