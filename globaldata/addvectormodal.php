<!-- Add Vector Map Modal -->
<div id="addvectormodal" class="modal fade " role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Vector Map</h4>
            </div>
            <form class="form-horizontal" id="postitemaction">
                <div class="modal-body">
                    <div class="form-group hidden">
                        <div class="col-md-3">
                            <input type="text" name="add_vectorid" id="add_vectorid" class="form-control" />  
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Bay</label>
                        <div class="col-sm-3">
                            <input type="text" name="add_baymodal" id="add_baymodal" class="form-control" placeholder="" tabindex="1" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Y-Position</label>
                        <div class="col-sm-3">
                            <input type="text" name="add_yposmodal" id="add_yposmodal" class="form-control" placeholder="" tabindex="2" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">X-Position</label>
                        <div class="col-sm-3">
                            <input type="text" name="add_xposmodal" id="add_xposmodal" class="form-control" placeholder="" tabindex="3" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Bay Height</label>
                        <div class="col-sm-3">
                            <input type="text" name="add_bayheightmodal" id="add_bayheightmodal" class="form-control" placeholder="" tabindex="4" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Bay Width</label>
                        <div class="col-sm-3">
                            <input type="text" name="add_baywidthmodal" id="add_baywidthmodal" class="form-control" placeholder="" tabindex="5" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Walk MM</label>
                        <div class="col-sm-3">
                            <input type="text" name="add_walkmodal" id="add_walkmodal" class="form-control" placeholder="" tabindex="6" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Case/Loose</label>
                        <div class="col-sm-3">
                            <input type="text" name="add_cselsemodal" id="add_cselsemodal" class="form-control" placeholder="" tabindex="7" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Tier</label>
                        <div class="col-sm-3">
                            <input type="text" name="add_tiermodal" id="add_tiermodal" class="form-control" placeholder="" tabindex="8" />
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-lg pull-left" name="add_submititemaction" id="add_submititemaction">Add Vector Settings</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
