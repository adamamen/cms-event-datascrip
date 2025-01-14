<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">

    <?php if(!empty($masterEvent)): ?>
        <?php $__currentLoopData = $masterEvent; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <title><?php echo $__env->yieldContent('title'); ?> &mdash; <?php echo e(ucwords($value['title']) . ' Event'); ?> </title>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php else: ?>
        <title><?php echo $__env->yieldContent('title'); ?> &mdash; CMS</title>
    <?php endif; ?>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="<?php echo e(asset('library/bootstrap/dist/css/bootstrap.min.css')); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <?php echo $__env->yieldPushContent('style'); ?>

    <!-- Template CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/components.css')); ?>">

    <!-- Start GA -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-94034622-3"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-94034622-3');
    </script>
    <!-- END GA -->
</head>
</head>

<body>
    <div id="app">
        <div class="main-wrapper">
            <!-- Header -->
            <?php echo $__env->make('components.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <?php if(!empty($title)): ?>
                <?php if($title != 'landing-page-qr'): ?>
                    <?php echo $__env->make('components.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php endif; ?>
            <?php else: ?>
                <?php echo $__env->make('components.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endif; ?>

            <!-- Content -->
            <?php echo $__env->yieldContent('main'); ?>

            <!-- Footer -->
            <?php echo $__env->make('components.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    </div>

    <!-- General JS Scripts -->
    <script src="<?php echo e(asset('library/jquery/dist/jquery.min.js')); ?>"></script>
    <script src="<?php echo e(asset('library/popper.js/dist/umd/popper.js')); ?>"></script>
    <script src="<?php echo e(asset('library/tooltip.js/dist/umd/tooltip.js')); ?>"></script>
    <script src="<?php echo e(asset('library/bootstrap/dist/js/bootstrap.min.js')); ?>"></script>
    <script src="<?php echo e(asset('library/jquery.nicescroll/dist/jquery.nicescroll.min.js')); ?>"></script>
    <script src="<?php echo e(asset('library/moment/min/moment.min.js')); ?>"></script>
    <script src="<?php echo e(asset('js/stisla.js')); ?>"></script>

    <?php echo $__env->yieldPushContent('scripts'); ?>

    <!-- Template JS File -->
    <script src="<?php echo e(asset('js/scripts.js')); ?>"></script>
    <script src="<?php echo e(asset('js/custom.js')); ?>"></script>
</body>

</html>
<?php /**PATH C:\laragon\www\cms-event-datascrip\resources\views/layouts/app.blade.php ENDPATH**/ ?>