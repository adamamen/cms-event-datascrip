

<?php $__env->startSection('title', 'Report Visitor Event'); ?>

<?php $__env->startPush('style'); ?>
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="<?php echo e(asset('library/jqvmap/dist/jqvmap.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('library/summernote/dist/summernote-bs4.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('library/datatables/media/css/jquery.dataTables.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('library/bootstrap-daterangepicker/daterangepicker.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('library/select2/dist/css/select2.min.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('main'); ?>
    <div class="main-content">
        <section class="section">
            <?php if($titleUrl == 'cms'): ?>
                <div class="section-header">
                    <h1>Report Visitor Event</h1>
                </div>
            <?php else: ?>
                <div class="section-header">
                    <h1>Data Report Visitor Event</h1>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Data Report Visitor Event</h4>
                            &emsp;
                            <div class="article-cta">
                                <a href="<?php echo e(route('export.excel.report.visitor', ['page' => $page])); ?>"
                                    class="btn btn-success"><i class="fa-solid fa-file-excel"></i>&emsp; Export Excel</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
                            <div class="table-responsive">
                                <table class="table-striped table" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Name</th>
                                            <th>Gender</th>
                                            <th>E-mail</th>
                                            <th>Whatsapp Number</th>
                                            <th>Institution</th>
                                            <th>Institution Name</th>
                                            <th>Event</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <!-- JS Libraies -->
    <script src="<?php echo e(asset('library/simpleweather/jquery.simpleWeather.min.js')); ?>"></script>
    <script src="<?php echo e(asset('library/chart.js/dist/Chart.min.js')); ?>"></script>
    <script src="<?php echo e(asset('library/jqvmap/dist/jquery.vmap.min.js')); ?>"></script>
    <script src="<?php echo e(asset('library/jqvmap/dist/maps/jquery.vmap.world.js')); ?>"></script>
    <script src="<?php echo e(asset('library/summernote/dist/summernote-bs4.min.js')); ?>"></script>
    <script src="<?php echo e(asset('library/chocolat/dist/js/jquery.chocolat.min.js')); ?>"></script>
    <script src="<?php echo e(asset('library/jquery-ui-dist/jquery-ui.min.js')); ?>"></script>
    <script src="<?php echo e(asset('library/datatables/media/js/jquery.dataTables.min.js')); ?>"></script>
    <script src="<?php echo e(asset('library/bootstrap-daterangepicker/daterangepicker.js')); ?>"></script>

    <!-- Page Specific JS File -->
    <script src="<?php echo e(asset('js/page/modules-datatables.js')); ?>"></script>
    <script src="<?php echo e(asset('library/sweetalert/dist/sweetalert.min.js')); ?>"></script>

    <!-- Page Specific JS File -->
    <script src="<?php echo e(asset('js/page/modules-sweetalert.js')); ?>"></script>
    <script>
        //
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\cms-event-datascrip\resources\views/report_visitor_event/index.blade.php ENDPATH**/ ?>