<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <ul class="sidebar-menu">
            <?php if(!empty($masterEvent)): ?>
                <?php $__currentLoopData = $masterEvent; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(!empty($id)): ?>
                        <?php if($id == Auth::user()->id): ?>
                            <div class="sidebar-brand">
                                <a href="<?php echo e(route('dashboard', ['page' => $value['title_url']])); ?>">
                                    <img src="<?php echo e(asset('images/' . $value['logo'])); ?>" height="54">
                                </a>
                            </div>
                            <div class="sidebar-brand sidebar-brand-sm">
                                <a href="<?php echo e(route('dashboard', ['page' => $value['title_url']])); ?>">
                                    <img src="<?php echo e(asset('images/' . $value['logo'])); ?>" height="15">
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="sidebar-brand">
                                <a href="<?php echo e(route('visitor_event.index', ['page' => $value['title_url']])); ?>">
                                    <img src="<?php echo e(asset('images/' . $value['logo'])); ?>" height="54">
                                </a>
                            </div>
                            <div class="sidebar-brand sidebar-brand-sm">
                                <a href="<?php echo e(route('visitor_event.index', ['page' => $value['title_url']])); ?>">
                                    <img src="<?php echo e(asset('images/' . $value['logo'])); ?>" height="10">
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="sidebar-brand">
                            <a href="#">
                                <img src="<?php echo e(asset('images/' . $value['logo'])); ?>" height="54">
                            </a>
                        </div>
                        <div class="sidebar-brand sidebar-brand-sm">
                            <a href="#">
                                <img src="<?php echo e(asset('images/' . $value['logo'])); ?>" height="15">
                            </a>
                        </div>
                    <?php endif; ?>

                    <?php if(!empty($id)): ?>
                        <?php if($id == Auth::user()->id): ?>
                            <li class="menu-header">Dashboard</li>
                            <li class="<?php echo e($type_menu == 'dashboard' ? 'active' : ''); ?>">
                                <a class="nav-link" href="<?php echo e(route('dashboard', ['page' => $value['title_url']])); ?>">
                                    <i class="fas fa-home"></i> <span>Dashboard</span>
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if(!empty(Auth::user()->id) && $type_menu != 'register_visitor'): ?>
                        <li class="menu-header">Master</li>
                        <li class="<?php echo e($type_menu == 'whatsapp_event' ? 'active' : ''); ?>">
                            <a class="nav-link"
                                href="<?php echo e(route('whatsapp_event.index', ['page' => $value['title_url']])); ?>">
                                <i class="fab fa-whatsapp"></i> <span>WhatsApp Event </span>
                            </a>
                        </li>
                        <li class="<?php echo e($type_menu == 'email_event' ? 'active' : ''); ?>">
                            <a class="nav-link"
                                href="<?php echo e(route('email_event.index', ['page' => $value['title_url']])); ?>">
                                <i class="fas fa-envelope"></i> <span>E-mail Event</span>
                            </a>
                        </li>
                        <?php if(!empty($id)): ?>
                            <?php if($id == Auth::user()->id): ?>
                                <li class="<?php echo e($type_menu == 'admin_event' ? 'active' : ''); ?>">
                                    <a class="nav-link"
                                        href="<?php echo e(route('admin_event.index', ['page' => $value['title_url']])); ?>"><i
                                            class="fas fa-user"></i> <span>Admin Event</span></a>
                                </li>
                            <?php endif; ?>
                        <?php endif; ?>
                        <li class="menu-header">Report</li>
                        <li class="<?php echo e($type_menu == 'visitor_event' ? 'active' : ''); ?>">
                            <a class="nav-link"
                                href="<?php echo e(route('visitor_event.index', ['page' => $value['title_url']])); ?>"><i
                                    class="fas fa-eye"></i> <span>Data Visitor Event </span></a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <?php if(!empty($title)): ?>
                    <?php if($title != 'landing-page-qr'): ?>
                        <div class="sidebar-brand">
                            <a href="<?php echo e(route('dashboard', ['page' => 'cms'])); ?>">
                                <img src="<?php echo e(asset('img/datascrip-logo.png')); ?>" height="54">
                            </a>
                        </div>
                        <div class="sidebar-brand sidebar-brand-sm">
                            <a href="<?php echo e(route('dashboard', ['page' => 'cms'])); ?>">
                                <img src="<?php echo e(asset('img/datascrip-logo-2.jpeg')); ?>" height="50">
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="sidebar-brand">
                            <a href="#">
                                <span>View Link QR</span>
                            </a>
                        </div>
                        <div class="sidebar-brand sidebar-brand-sm">
                            <a href="#">
                                <span>VLQ</span>
                            </a>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="sidebar-brand">
                        <a href="<?php echo e(route('dashboard', ['page' => 'cms'])); ?>">
                            <img src="<?php echo e(asset('img/datascrip-logo.png')); ?>" height="54">
                        </a>
                    </div>
                    <div class="sidebar-brand sidebar-brand-sm">
                        <a href="<?php echo e(route('dashboard', ['page' => 'cms'])); ?>">
                            <img src="<?php echo e(asset('img/datascrip-logo-2.jpeg')); ?>" height="50">
                        </a>
                    </div>
                <?php endif; ?>

                <?php if(!empty($type_menu)): ?>
                    <li class="menu-header">Dashboard</li>
                    <li class="<?php echo e($type_menu == 'dashboard' ? 'active' : ''); ?>">
                        <a class="nav-link" href="<?php echo e(route('dashboard', ['page' => 'cms'])); ?>"><i
                                class="fas fa-home"></i>
                            <span>Dashboard</span></a>
                    </li>
                    <li class="menu-header">Master</li>

                    <?php if(empty(Auth::user()->divisi) && Auth::user()->event_id == 0): ?>
                        <li class="<?php echo e($type_menu == 'company_event' ? 'active' : ''); ?>">
                            <a class="nav-link" href="<?php echo e(route('company_event.index', ['page' => 'cms'])); ?>"><i
                                    class="fas fa-building"></i> <span>Division Event</span></a>
                        </li>
                    <?php endif; ?>

                    <li class="<?php echo e($type_menu == 'master_event' ? 'active' : ''); ?>">
                        <a class="nav-link" href="<?php echo e(route('index', ['page' => 'cms'])); ?>"><i
                                class="fas fa-calendar"></i>
                            <span>Master Event</span></a>
                    </li>
                    <li class="<?php echo e($type_menu == 'whatsapp_event' ? 'active' : ''); ?>">
                        <a class="nav-link" href="<?php echo e(route('whatsapp_event.index', ['page' => 'cms'])); ?>">
                            <i class="fab fa-whatsapp"></i> <span>WhatsApp Event</span>
                        </a>
                    </li>
                    <li class="<?php echo e($type_menu == 'email_event' ? 'active' : ''); ?>">
                        <a class="nav-link" href="<?php echo e(route('email_event.index', ['page' => 'cms'])); ?>">
                            <i class="fas fa-envelope"></i> <span>E-mail Event</span>
                        </a>
                    </li>
                    <li class="<?php echo e($type_menu == 'admin_event' ? 'active' : ''); ?>">
                        <a class="nav-link" href="<?php echo e(route('admin_event.index', ['page' => 'cms'])); ?>"><i
                                class="fas fa-user-shield"></i> <span>Admin Event</span></a>
                    </li>
                    <li class="<?php echo e($type_menu == 'master_user' ? 'active' : ''); ?>">
                        <a class="nav-link" href="<?php echo e(route('master_user.index', ['page' => 'cms'])); ?>"><i
                                class="fas fa-user-circle"></i> <span>Master User</span></a>
                    </li>
                    <?php if(empty(Auth::user()->divisi) && Auth::user()->event_id == 0): ?>
                        <li class="<?php echo e($type_menu == 'user_access' ? 'active' : ''); ?>">
                            <a class="nav-link" href="<?php echo e(route('user_access.index', ['page' => 'cms'])); ?>"><i
                                    class="fas fa-users-cog"></i><span>User Access</span></a>
                        </li>
                    <?php endif; ?>
                    <li class="menu-header">Report</li>
                    <li class="<?php echo e($type_menu == 'visitor_event' ? 'active' : ''); ?>">
                        <a class="nav-link" href="<?php echo e(route('visitor_event.index', ['page' => 'cms'])); ?>"><i
                                class="fas fa-eye"></i> <span>Data Visitor Event</span></a>
                    </li>
                    <li class="<?php echo e($type_menu == 'report_visitor_event' ? 'active' : ''); ?>">
                        <a class="nav-link" href="<?php echo e(route('report_visitor_event.index', ['page' => 'cms'])); ?>"><i
                                class="fas fa-file-excel"></i>
                            <span>Report Visitor Event</span></a>
                    </li>
                <?php endif; ?>
            <?php endif; ?>
        </ul>
    </aside>
</div>
<?php /**PATH C:\laragon\www\cms-event-datascrip\resources\views/components/sidebar.blade.php ENDPATH**/ ?>