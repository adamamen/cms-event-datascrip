

<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startPush('style'); ?>
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="<?php echo e(asset('library/jqvmap/dist/jqvmap.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('library/summernote/dist/summernote-bs4.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('library/owl.carousel/dist/assets/owl.carousel.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('library/owl.carousel/dist/assets/owl.theme.default.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('library/flag-icon-css/css/flag-icon.min.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('main'); ?>
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Dashboard</h1>
            </div>
            <div class="row">
                <?php if(!empty($masterEvent)): ?>
                    <?php $__currentLoopData = $masterEvent; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-6">
                            <div class="card card-hero">
                                <div class="card-header">
                                    <div class="card-icon">
                                        <i class="fas fa-eye"></i>
                                    </div>
                                    <h4><?php echo e(!empty($totalVisitor) ? $totalVisitor : '0'); ?></h4>
                                    <a href="<?php echo e(route('visitor_event.index', ['page' => $value['title_url']])); ?>">
                                        <div class="card-description" style="color:white">Total Data Visitor Event</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-hero">
                                <div class="card-header">
                                    <div class="card-icon">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <h4><?php echo e($totalAdmin); ?></h4>
                                    <a href="<?php echo e(route('admin_event.index', ['page' => $value['title_url']])); ?>">
                                        <div class="card-description" style="color:white">Total Admin Event</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-hero">
                                <div class="card-header">
                                    <div class="card-icon">
                                        <i class="fab fa-whatsapp"></i>
                                    </div>
                                    <h4><?php echo e($totalWhatsapp); ?></h4>
                                    <a href="<?php echo e(route('whatsapp_event.index', ['page' => $value['title_url']])); ?>">
                                        <div class="card-description" style="color:white">Total WhatsApp Event</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-hero">
                                <div class="card-header">
                                    <div class="card-icon">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <h4><?php echo e($totalEmail); ?></h4>
                                    <a href="<?php echo e(route('email_event.index', ['page' => $value['title_url']])); ?>">
                                        <div class="card-description" style="color:white">Total E-mail Event</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <?php if(empty(Auth::user()->divisi) && Auth::user()->event_id == 0): ?>
                        <div class="col-md-6">
                            <div class="card card-hero">
                                <div class="card-header">
                                    <div class="card-icon">
                                        <i class="fas fa-building"></i>
                                    </div>
                                    <h4><?php echo e($totalDivisi); ?></h4>
                                    <a href="<?php echo e(route('company_event.index', ['page' => 'cms'])); ?>">
                                        <div class="card-description" style="color:white">Total Division Event</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="col-md-6">
                        <div class="card card-hero">
                            <div class="card-header">
                                <div class="card-icon">
                                    <i class="fas fa-calendar"></i>
                                </div>
                                <h4><?php echo e($totalEvent); ?></h4>
                                <a href="<?php echo e(route('index', ['page' => 'cms'])); ?>">
                                    <div class="card-description" style="color:white">Total Master Event</div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-hero">
                            <div class="card-header">
                                <div class="card-icon">
                                    <i class="fab fa-whatsapp"></i>
                                </div>
                                <h4><?php echo e($totalWhatsapp); ?></h4>
                                <a href="<?php echo e(route('whatsapp_event.index', ['page' => 'cms'])); ?>">
                                    <div class="card-description" style="color:white">Total WhatsApp Event</div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-hero">
                            <div class="card-header">
                                <div class="card-icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <h4><?php echo e($totalEmail); ?></h4>
                                <a href="<?php echo e(route('email_event.index', ['page' => 'cms'])); ?>">
                                    <div class="card-description" style="color:white">Total E-mail Event</div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-hero">
                            <div class="card-header">
                                <div class="card-icon">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                                <h4><?php echo e($totalMasterUser); ?></h4>
                                <a href="<?php echo e(route('master_user.index', ['page' => 'cms'])); ?>">
                                    <div class="card-description" style="color:white">Total Master User</div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php if(empty(Auth::user()->divisi) && Auth::user()->event_id == 0): ?>
                        <div class="col-md-6">
                            <div class="card card-hero">
                                <div class="card-header">
                                    <div class="card-icon">
                                        <i class="fas fa-users-cog"></i>
                                    </div>
                                    <h4><?php echo e($totalUserAccess); ?></h4>
                                    <a href="<?php echo e(route('user_access.index', ['page' => 'cms'])); ?>">
                                        <div class="card-description" style="color:white">Total User Access</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="col-md-6">
                        <div class="card card-hero">
                            <div class="card-header">
                                <div class="card-icon">
                                    <i class="fas fa-user"></i>
                                </div>
                                <h4><?php echo e($totalAdmin); ?></h4>
                                <a href="<?php echo e(route('admin_event.index', ['page' => 'cms'])); ?>">
                                    <div class="card-description" style="color:white">Total Admin Event</div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-hero">
                            <div class="card-header">
                                <div class="card-icon">
                                    <i class="fas fa-eye"></i>
                                </div>
                                <h4><?php echo e($totalVisitor); ?></h4>
                                <a href="<?php echo e(route('visitor_event.index', ['page' => 'cms'])); ?>">
                                    <div class="card-description" style="color:white">Total Data Visitor Event</div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6" hidden>
                        <div class="card card-hero">
                            <div class="card-header">
                                <div class="card-icon">
                                    <i class="fas fa-file-excel"></i>
                                </div>
                                <h4><?php echo e($totalReportVisitor); ?></h4>
                                <a href="<?php echo e(route('report_visitor_event.index', ['page' => 'cms'])); ?>">
                                    <div class="card-description" style="color:white">Total Report Visitor Event</div>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <!-- JS Libraies -->
    <script src="<?php echo e(asset('library/jquery-sparkline/jquery.sparkline.min.js')); ?>"></script>
    <script src="<?php echo e(asset('library/chart.js/dist/Chart.js')); ?>"></script>
    <script src="<?php echo e(asset('library/owl.carousel/dist/owl.carousel.min.js')); ?>"></script>
    <script src="<?php echo e(asset('library/summernote/dist/summernote-bs4.min.js')); ?>"></script>
    <script src="<?php echo e(asset('library/chocolat/dist/js/jquery.chocolat.min.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\cms-event-datascrip\resources\views/dashboard/index.blade.php ENDPATH**/ ?>