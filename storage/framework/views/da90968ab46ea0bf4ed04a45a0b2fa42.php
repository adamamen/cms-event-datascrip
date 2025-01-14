<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar">
    <form class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">

            <?php if(!empty($title)): ?>
                <?php if($title != 'landing-page-qr'): ?>
                    <li>
                        <a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a>
                    </li>
                <?php endif; ?>
            <?php else: ?>
                <li>
                    <a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a>
                </li>

            <?php endif; ?>

        </ul>
    </form>
    <?php if(!empty(Auth::user()->full_name)): ?>
        <ul class="navbar-nav navbar-right">
            <li class="dropdown"><a href="#" data-toggle="dropdown"
                    class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                    <img alt="image" src="<?php echo e(asset('img/avatar/avatar-1.png')); ?>" class="rounded-circle mr-1">
                    <div class="d-sm-none d-lg-inline-block"><?php echo e(Auth::user()->full_name); ?></div>
                </a>
                <div class="dropdown-menu dropdown-menu-right" style="margin-top: 6%;">
                    <?php if(!empty($masterEvent)): ?>
                        <?php $__currentLoopData = $masterEvent; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e(route('logout', ['page' => $value['title_url']])); ?>"
                                class="dropdown-item has-icon text-danger">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <a href="<?php echo e(route('logout', ['page' => 'cms'])); ?>" class="dropdown-item has-icon text-danger">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    <?php endif; ?>
                </div>
            </li>
        </ul>
    <?php endif; ?>

</nav>
<?php /**PATH C:\laragon\www\cms-event-datascrip\resources\views/components/header.blade.php ENDPATH**/ ?>