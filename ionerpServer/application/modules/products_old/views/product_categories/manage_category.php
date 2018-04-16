<?php ?>
<div class="container-fluid" style="min-height: 100%; height: 415px; margin: 0px auto;">
    <div class="row" style="min-height: 100%; height: 100%; margin: 0px auto;">
        <div class="col-sm-4 no-pad">
            <div class="row-fluid">
                <div class="col-sm-12 no-pad">
                    <button id="btnExpandAll" class="btn btn-xs btn-primary">Expand/Collapse all</button>    
                    <button id="btnSort" class="btn btn-xs btn-warning">Sort</button>
                </div>
                <div class="row-fluid no-pad">
                    <div class="col-sm-12 no-pad" id="category_tree">
                        <?php
                        $list = "<ul class='no_bg_color'>";
                        foreach ($categories as $cat) {
                            $list.="<li id=\"" . $cat['id'] . "\">" . $cat['name'];
                            if (array_key_exists('children', $cat)) {
                                $list .= "<ul>";
                                foreach ($cat['children'] as $scat) {
                                    $list.="<li id=\"" . $cat['id'] . "\">" . $cat['name'] . '</li>';
                                }
                                $list.="</ul>";
                            }

                            $list.="</li>";
                        }
                        $list.="</ul>";
                        echo $list;
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-8 no-pad" style="min-height: 100%;height: 100%;">
            <div class="nav-tabs-custom" style="min-height: 100%; height: 100%; margin: 0px auto; position: absolute;">
                <ul class="nav nav-tabs ">
                    <li class="pull-left header"><i class="fa fa-gears"></i> Category</li>
                    <li class="active"><a data-toggle="tab" href="#tab_1-1" aria-expanded="true">Tab 1</a></li>
                    <li class=""><a data-toggle="tab" href="#tab_2-2" aria-expanded="false">Tab 2</a></li>
                    <li class=""><a data-toggle="tab" href="#tab_3-2" aria-expanded="false">Tab 3</a></li>
                    <li class="dropdown">
                        <a href="#" data-toggle="dropdown" class="dropdown-toggle" aria-expanded="false">
                            Dropdown <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li role="presentation"><a href="#" tabindex="-1" role="menuitem">Action</a></li>
                            <li role="presentation"><a href="#" tabindex="-1" role="menuitem">Another action</a></li>
                            <li role="presentation"><a href="#" tabindex="-1" role="menuitem">Something else here</a></li>
                            <li class="divider" role="presentation"></li>
                            <li role="presentation"><a href="#" tabindex="-1" role="menuitem">Separated link</a></li>
                        </ul>
                    </li>

                </ul>
                <div class="tab-content">
                    <div id="tab_1-1" class="tab-pane active">
                        <b>How to use:</b>

                        <p>Exactly like the original bootstrap tabs except you should use
                            the custom wrapper <code>.nav-tabs-custom</code> to achieve this style.</p>
                        A wonderful serenity has taken possession of my entire soul,
                        like these sweet mornings of spring which I enjoy with my whole heart.
                        I am alone, and feel the charm of existence in this spot,
                        which was created for the bliss of souls like mine. I am so happy,
                        my dear friend, so absorbed in the exquisite sense of mere tranquil existence,
                        that I neglect my talents. I should be incapable of drawing a single stroke
                        at the present moment; and yet I feel that I never was a greater artist than now.
                    </div>
                    <!-- /.tab-pane -->
                    <div id="tab_2-2" class="tab-pane">
                        The European languages are members of the same family. Their separate existence is a myth.
                        For science, music, sport, etc, Europe uses the same vocabulary. The languages only differ
                        in their grammar, their pronunciation and their most common words. Everyone realizes why a
                        new common language would be desirable: one could refuse to pay expensive translators. To
                        achieve this, it would be necessary to have uniform grammar, pronunciation and more common
                        words. If several languages coalesce, the grammar of the resulting language is more simple
                        and regular than that of the individual languages.
                    </div>
                    <!-- /.tab-pane -->
                    <div id="tab_3-2" class="tab-pane">
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                        Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
                        when an unknown printer took a galley of type and scrambled it to make a type specimen book.
                        It has survived not only five centuries, but also the leap into electronic typesetting,
                        remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset
                        sheets containing Lorem Ipsum passages, and more recently with desktop publishing software
                        like Aldus PageMaker including versions of Lorem Ipsum.
                    </div>
                    <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
            </div>
        </div>
    </div>
</div>
