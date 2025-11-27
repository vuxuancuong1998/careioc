<aside id="side-overlay" style="display: none;">
            <div class="bg-image" style="background-image: url('<?php echo $template_path;?>/backend/assets/media/various/bg_side_overlay_header.jpg');">
               <div class="bg-primary-op">
                  <div class="content-header">
                     <a class="img-link mr-1" href="be_pages_generic_profile.html">
                     <img class="img-avatar img-avatar48" src="<?php echo $template_path;?>/backend/assets/media/avatars/avatar10.jpg" alt="">
                     </a>
                     <div class="ml-2">
                        <a class="text-white font-w600" href="be_pages_generic_profile.html">George Taylor</a>
                        <div class="text-white-75 font-size-sm">Full Stack Developer</div>
                     </div>
                     <a class="ml-auto text-white" href="javascript:void(0)" data-toggle="layout" data-action="side_overlay_close">
                     <i class="fa fa-times-circle"></i>
                     </a>
                  </div>
               </div>
            </div>
            <div class="content-side">
               <div class="block block-transparent pull-x pull-t">
                  <ul class="nav nav-tabs nav-tabs-block nav-justified" data-toggle="tabs" role="tablist">
                     <li class="nav-item">
                        <a class="nav-link active" href="#so-settings">
                        <i class="fa fa-fw fa-cog"></i>
                        </a>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link" href="#so-people">
                        <i class="far fa-fw fa-user-circle"></i>
                        </a>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link" href="#so-profile">
                        <i class="far fa-fw fa-edit"></i>
                        </a>
                     </li>
                  </ul>
                  <div class="block-content tab-content overflow-hidden">
                     <div class="tab-pane pull-x fade fade-up show active" id="so-settings" role="tabpanel">
                        <div class="block mb-0">
                           <div class="block-content block-content-sm block-content-full bg-body">
                              <span class="text-uppercase font-size-sm font-w700">Color Themes</span>
                           </div>
                           <div class="block-content block-content-full">
                              <div class="row gutters-tiny text-center">
                                 <div class="col-4 mb-1">
                                    <a class="d-block py-3 text-white font-size-sm font-w600 bg-default" data-toggle="theme" data-theme="default" href="#">
                                    Default
                                    </a>
                                 </div>
                                 <div class="col-4 mb-1">
                                    <a class="d-block py-3 text-white font-size-sm font-w600 bg-xwork" data-toggle="theme" data-theme="<?php echo $template_path;?>/backend/assets/css/themes/xwork.min-3.1.css" href="#">
                                    xWork
                                    </a>
                                 </div>
                                 <div class="col-4 mb-1">
                                    <a class="d-block py-3 text-white font-size-sm font-w600 bg-xmodern" data-toggle="theme" data-theme="<?php echo $template_path;?>/backend/assets/css/themes/xmodern.min-3.1.css" href="#">
                                    xModern
                                    </a>
                                 </div>
                                 <div class="col-4 mb-1">
                                    <a class="d-block py-3 text-white font-size-sm font-w600 bg-xeco" data-toggle="theme" data-theme="<?php echo $template_path;?>/backend/assets/css/themes/xeco.min-3.1.css" href="#">
                                    xEco
                                    </a>
                                 </div>
                                 <div class="col-4 mb-1">
                                    <a class="d-block py-3 text-white font-size-sm font-w600 bg-xsmooth" data-toggle="theme" data-theme="<?php echo $template_path;?>/backend/assets/css/themes/xsmooth.min-3.1.css" href="#">
                                    xSmooth
                                    </a>
                                 </div>
                                 <div class="col-4 mb-1">
                                    <a class="d-block py-3 text-white font-size-sm font-w600 bg-xinspire" data-toggle="theme" data-theme="<?php echo $template_path;?>/backend/assets/css/themes/xinspire.min-3.1.css" href="#">
                                    xInspire
                                    </a>
                                 </div>
                                 <div class="col-4 mb-1">
                                    <a class="d-block py-3 text-white font-size-sm font-w600 bg-xdream" data-toggle="theme" data-theme="<?php echo $template_path;?>/backend/assets/css/themes/xdream.min-3.1.css" href="#">
                                    xDream
                                    </a>
                                 </div>
                                 <div class="col-4 mb-1">
                                    <a class="d-block py-3 text-white font-size-sm font-w600 bg-xpro" data-toggle="theme" data-theme="<?php echo $template_path;?>/backend/assets/css/themes/xpro.min-3.1.css" href="#">
                                    xPro
                                    </a>
                                 </div>
                                 <div class="col-4 mb-1">
                                    <a class="d-block py-3 text-white font-size-sm font-w600 bg-xplay" data-toggle="theme" data-theme="<?php echo $template_path;?>/backend/assets/css/themes/xplay.min-3.1.css" href="#">
                                    xPlay
                                    </a>
                                 </div>
                                 <div class="col-12">
                                    <a class="d-block py-3 bg-body-dark font-w600 text-dark" href="be_ui_color_themes.html">All Color Themes</a>
                                 </div>
                              </div>
                           </div>
                           <div class="block-content block-content-sm block-content-full bg-body">
                              <span class="text-uppercase font-size-sm font-w700">Sidebar</span>
                           </div>
                           <div class="block-content block-content-full">
                              <div class="row gutters-tiny text-center">
                                 <div class="col-6 mb-1">
                                    <a class="d-block py-3 bg-body-dark font-w600 text-dark" data-toggle="layout" data-action="sidebar_style_dark" href="javascript:void(0)">Dark</a>
                                 </div>
                                 <div class="col-6 mb-1">
                                    <a class="d-block py-3 bg-body-dark font-w600 text-dark" data-toggle="layout" data-action="sidebar_style_light" href="javascript:void(0)">Light</a>
                                 </div>
                              </div>
                           </div>
                           <div class="block-content block-content-sm block-content-full bg-body">
                              <span class="text-uppercase font-size-sm font-w700">Header</span>
                           </div>
                           <div class="block-content block-content-full">
                              <div class="row gutters-tiny text-center mb-2">
                                 <div class="col-6 mb-1">
                                    <a class="d-block py-3 bg-body-dark font-w600 text-dark" data-toggle="layout" data-action="header_style_dark" href="javascript:void(0)">Dark</a>
                                 </div>
                                 <div class="col-6 mb-1">
                                    <a class="d-block py-3 bg-body-dark font-w600 text-dark" data-toggle="layout" data-action="header_style_light" href="javascript:void(0)">Light</a>
                                 </div>
                                 <div class="col-6 mb-1">
                                    <a class="d-block py-3 bg-body-dark font-w600 text-dark" data-toggle="layout" data-action="header_mode_fixed" href="javascript:void(0)">Fixed</a>
                                 </div>
                                 <div class="col-6 mb-1">
                                    <a class="d-block py-3 bg-body-dark font-w600 text-dark" data-toggle="layout" data-action="header_mode_static" href="javascript:void(0)">Static</a>
                                 </div>
                              </div>
                           </div>
                           <div class="block-content block-content-sm block-content-full bg-body">
                              <span class="text-uppercase font-size-sm font-w700">Content</span>
                           </div>
                           <div class="block-content block-content-full">
                              <div class="row gutters-tiny text-center">
                                 <div class="col-6 mb-1">
                                    <a class="d-block py-3 bg-body-dark font-w600 text-dark" data-toggle="layout" data-action="content_layout_boxed" href="javascript:void(0)">Boxed</a>
                                 </div>
                                 <div class="col-6 mb-1">
                                    <a class="d-block py-3 bg-body-dark font-w600 text-dark" data-toggle="layout" data-action="content_layout_narrow" href="javascript:void(0)">Narrow</a>
                                 </div>
                                 <div class="col-12 mb-1">
                                    <a class="d-block py-3 bg-body-dark font-w600 text-dark" data-toggle="layout" data-action="content_layout_full_width" href="javascript:void(0)">Full Width</a>
                                 </div>
                              </div>
                           </div>
                           <div class="block-content row justify-content-center border-top">
                              <div class="col-9">
                                 <a class="btn btn-block btn-hero-primary" href="be_layout_api.html">
                                 <i class="fa fa-fw fa-flask mr-1"></i> Layout API
                                 </a>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="tab-pane pull-x fade fade-up" id="so-people" role="tabpanel">
                        <div class="block mb-0">
                           <div class="block-content block-content-sm block-content-full bg-body">
                              <span class="text-uppercase font-size-sm font-w700">Online</span>
                           </div>
                           <div class="block-content">
                              <ul class="nav-items">
                                 <li>
                                    <a class="media py-2" href="be_pages_generic_profile.html">
                                       <div class="mx-3 overlay-container">
                                          <img class="img-avatar img-avatar48" src="<?php echo $template_path;?>/backend/assets/media/avatars/avatar8.jpg" alt="">
                                          <span class="overlay-item item item-tiny item-circle border border-2x border-white bg-success"></span>
                                       </div>
                                       <div class="media-body">
                                          <div class="font-w600">Danielle Jones</div>
                                          <div class="font-size-sm text-muted">Photographer</div>
                                       </div>
                                    </a>
                                 </li>
                                 <li>
                                    <a class="media py-2" href="be_pages_generic_profile.html">
                                       <div class="mx-3 overlay-container">
                                          <img class="img-avatar img-avatar48" src="<?php echo $template_path;?>/backend/assets/media/avatars/avatar9.jpg" alt="">
                                          <span class="overlay-item item item-tiny item-circle border border-2x border-white bg-success"></span>
                                       </div>
                                       <div class="media-body">
                                          <div class="font-w600">Thomas Riley</div>
                                          <div class="font-w400 font-size-sm text-muted">Web Designer</div>
                                       </div>
                                    </a>
                                 </li>
                                 <li>
                                    <a class="media py-2" href="be_pages_generic_profile.html">
                                       <div class="mx-3 overlay-container">
                                          <img class="img-avatar img-avatar48" src="<?php echo $template_path;?>/backend/assets/media/avatars/avatar5.jpg" alt="">
                                          <span class="overlay-item item item-tiny item-circle border border-2x border-white bg-success"></span>
                                       </div>
                                       <div class="media-body">
                                          <div class="font-w600">Danielle Jones</div>
                                          <div class="font-w400 font-size-sm text-muted">Web Developer</div>
                                       </div>
                                    </a>
                                 </li>
                              </ul>
                           </div>
                           <div class="block-content block-content-sm block-content-full bg-body">
                              <span class="text-uppercase font-size-sm font-w700">Busy</span>
                           </div>
                           <div class="block-content">
                              <ul class="nav-items">
                                 <li>
                                    <a class="media py-2" href="be_pages_generic_profile.html">
                                       <div class="mx-3 overlay-container">
                                          <img class="img-avatar img-avatar48" src="<?php echo $template_path;?>/backend/assets/media/avatars/avatar7.jpg" alt="">
                                          <span class="overlay-item item item-tiny item-circle border border-2x border-white bg-danger"></span>
                                       </div>
                                       <div class="media-body">
                                          <div class="font-w600">Amanda Powell</div>
                                          <div class="font-w400 font-size-sm text-muted">UI Designer</div>
                                       </div>
                                    </a>
                                 </li>
                              </ul>
                           </div>
                           <div class="block-content block-content-sm block-content-full bg-body">
                              <span class="text-uppercase font-size-sm font-w700">Away</span>
                           </div>
                           <div class="block-content">
                              <ul class="nav-items">
                                 <li>
                                    <a class="media py-2" href="be_pages_generic_profile.html">
                                       <div class="mx-3 overlay-container">
                                          <img class="img-avatar img-avatar48" src="<?php echo $template_path;?>/backend/assets/media/avatars/avatar16.jpg" alt="">
                                          <span class="overlay-item item item-tiny item-circle border border-2x border-white bg-warning"></span>
                                       </div>
                                       <div class="media-body">
                                          <div class="font-w600">Jesse Fisher</div>
                                          <div class="font-w400 font-size-sm text-muted">Copywriter</div>
                                       </div>
                                    </a>
                                 </li>
                                 <li>
                                    <a class="media py-2" href="be_pages_generic_profile.html">
                                       <div class="mx-3 overlay-container">
                                          <img class="img-avatar img-avatar48" src="<?php echo $template_path;?>/backend/assets/media/avatars/avatar1.jpg" alt="">
                                          <span class="overlay-item item item-tiny item-circle border border-2x border-white bg-warning"></span>
                                       </div>
                                       <div class="media-body">
                                          <div class="font-w600">Lori Grant</div>
                                          <div class="font-w400 font-size-sm text-muted">Writer</div>
                                       </div>
                                    </a>
                                 </li>
                              </ul>
                           </div>
                           <div class="block-content block-content-sm block-content-full bg-body">
                              <span class="text-uppercase font-size-sm font-w700">Offline</span>
                           </div>
                           <div class="block-content">
                              <ul class="nav-items">
                                 <li>
                                    <a class="media py-2" href="be_pages_generic_profile.html">
                                       <div class="mx-3 overlay-container">
                                          <img class="img-avatar img-avatar48" src="<?php echo $template_path;?>/backend/assets/media/avatars/avatar9.jpg" alt="">
                                          <span class="overlay-item item item-tiny item-circle border border-2x border-white bg-muted"></span>
                                       </div>
                                       <div class="media-body">
                                          <div class="font-w600">Brian Cruz</div>
                                          <div class="font-w400 font-size-sm text-muted">Teacher</div>
                                       </div>
                                    </a>
                                 </li>
                                 <li>
                                    <a class="media py-2" href="be_pages_generic_profile.html">
                                       <div class="mx-3 overlay-container">
                                          <img class="img-avatar img-avatar48" src="<?php echo $template_path;?>/backend/assets/media/avatars/avatar1.jpg" alt="">
                                          <span class="overlay-item item item-tiny item-circle border border-2x border-white bg-muted"></span>
                                       </div>
                                       <div class="media-body">
                                          <div class="font-w600">Alice Moore</div>
                                          <div class="font-w400 font-size-sm text-muted">Photographer</div>
                                       </div>
                                    </a>
                                 </li>
                                 <li>
                                    <a class="media py-2" href="be_pages_generic_profile.html">
                                       <div class="mx-3 overlay-container">
                                          <img class="img-avatar img-avatar48" src="<?php echo $template_path;?>/backend/assets/media/avatars/avatar8.jpg" alt="">
                                          <span class="overlay-item item item-tiny item-circle border border-2x border-white bg-muted"></span>
                                       </div>
                                       <div class="media-body">
                                          <div class="font-w600">Laura Carr</div>
                                          <div class="font-w400 font-size-sm text-muted">Front-end Developer</div>
                                       </div>
                                    </a>
                                 </li>
                                 <li>
                                    <a class="media py-2" href="be_pages_generic_profile.html">
                                       <div class="mx-3 overlay-container">
                                          <img class="img-avatar img-avatar48" src="<?php echo $template_path;?>/backend/assets/media/avatars/avatar9.jpg" alt="">
                                          <span class="overlay-item item item-tiny item-circle border border-2x border-white bg-muted"></span>
                                       </div>
                                       <div class="media-body">
                                          <div class="font-w600">Ryan Flores</div>
                                          <div class="font-w400 font-size-sm text-muted">UX Specialist</div>
                                       </div>
                                    </a>
                                 </li>
                              </ul>
                           </div>
                           <div class="block-content row justify-content-center border-top">
                              <div class="col-9">
                                 <a class="btn btn-block btn-hero-primary" href="javascript:void(0)">
                                 <i class="fa fa-fw fa-plus mr-1"></i> Add People
                                 </a>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="tab-pane pull-x fade fade-up" id="so-profile" role="tabpanel">
                        <form action="https://demo.pixelcave.com/dashmix/be_pages_dashboard.html" method="POST" onsubmit="return false;">
                           <div class="block mb-0">
                              <div class="block-content block-content-sm block-content-full bg-body">
                                 <span class="text-uppercase font-size-sm font-w700">Personal</span>
                              </div>
                              <div class="block-content block-content-full">
                                 <div class="form-group">
                                    <label>Username</label>
                                    <input type="text" readonly class="form-control" id="staticEmail" value="Admin">
                                 </div>
                                 <div class="form-group">
                                    <label for="so-profile-name">Name</label>
                                    <input type="text" class="form-control" id="so-profile-name" name="so-profile-name" value="George Taylor">
                                 </div>
                                 <div class="form-group">
                                    <label for="so-profile-email">Email</label>
                                    <input type="email" class="form-control" id="so-profile-email" name="so-profile-email" value="g.taylor@example.com">
                                 </div>
                              </div>
                              <div class="block-content block-content-sm block-content-full bg-body">
                                 <span class="text-uppercase font-size-sm font-w700">Password Update</span>
                              </div>
                              <div class="block-content block-content-full">
                                 <div class="form-group">
                                    <label for="so-profile-password">Current Password</label>
                                    <input type="password" class="form-control" id="so-profile-password" name="so-profile-password">
                                 </div>
                                 <div class="form-group">
                                    <label for="so-profile-new-password">New Password</label>
                                    <input type="password" class="form-control" id="so-profile-new-password" name="so-profile-new-password">
                                 </div>
                                 <div class="form-group">
                                    <label for="so-profile-new-password-confirm">Confirm New Password</label>
                                    <input type="password" class="form-control" id="so-profile-new-password-confirm" name="so-profile-new-password-confirm">
                                 </div>
                              </div>
                              <div class="block-content block-content-sm block-content-full bg-body">
                                 <span class="text-uppercase font-size-sm font-w700">Options</span>
                              </div>
                              <div class="block-content">
                                 <div class="custom-control custom-checkbox custom-control-primary mb-1">
                                    <input type="checkbox" class="custom-control-input" id="so-settings-status" name="so-settings-status" value="1">
                                    <label class="custom-control-label" for="so-settings-status">Online Status</label>
                                 </div>
                                 <p class="text-muted font-size-sm">
                                    Make your online status visible to other users of your app
                                 </p>
                                 <div class="custom-control custom-checkbox custom-control-primary mb-1">
                                    <input type="checkbox" class="custom-control-input" id="so-settings-notifications" name="so-settings-notifications" value="1" checked>
                                    <label class="custom-control-label" for="so-settings-notifications">Notifications</label>
                                 </div>
                                 <p class="text-muted font-size-sm">
                                    Receive desktop notifications regarding your projects and sales
                                 </p>
                                 <div class="custom-control custom-checkbox custom-control-primary mb-1">
                                    <input type="checkbox" class="custom-control-input" id="so-settings-updates" name="so-settings-updates" value="1" checked>
                                    <label class="custom-control-label" for="so-settings-updates">Auto Updates</label>
                                 </div>
                                 <p class="text-muted font-size-sm">
                                    If enabled, we will keep all your applications and servers up to date with the most recent features automatically
                                 </p>
                              </div>
                              <div class="block-content row justify-content-center border-top">
                                 <div class="col-9">
                                    <button type="submit" class="btn btn-block btn-hero-primary">
                                    <i class="fa fa-fw fa-save mr-1"></i> Save
                                    </button>
                                 </div>
                              </div>
                           </div>
                        </form>
                     </div>
                  </div>
               </div>
            </div>
         </aside>