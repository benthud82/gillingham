
        <!-- Modify Bay Location Modal -->
        <div id="modifybaylocmodal" class="modal fade " role="dialog">
            <div class="modal-dialog modal-lg">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Modify Bay Location</h4>
                    </div>
                    <form class="form-horizontal" id="postitemaction_bayloc">
                        <div class="modal-body">
                            <div class="form-group hidden">
                                <div class="col-md-3">
                                    <input type="text" name="baylocid" id="baylocid" class="form-control" />  
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Location</label>
                                <div class="col-sm-3">
                                    <input type="text" name="locmodal_bayloc" id="locmodal_bayloc" class="form-control" placeholder="" tabindex="1" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Dim Group</label>
                                <div class="col-sm-3">
                                    <input type="text" name="dimgroupmodal_bayloc" id="dimgroupmodal_bayloc" class="form-control" placeholder="" tabindex="2" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Bay</label>
                                <div class="col-sm-5">
                                    <input type="text" name="baymodal_bayloc" id="baymodal_bayloc" maxlength="4" class="form-control" placeholder="To match with vector map (3207, 5208, etc.)" tabindex="3" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Walk Bay</label>
                                <div class="col-sm-5">
                                    <input type="text" name="walkbaymodal_bayloc" id="waklbaymodal_bayloc" class="form-control" maxlength="2" placeholder="Standard walk bay (01, 05, etc.)" tabindex="4" />
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <div class="">
                                <button type="submit" class="btn btn-primary btn-lg pull-left" name="submititemaction_bayloc" id="submititemaction_bayloc">Modify Bay Location Settings</button>
                                 <button type="submit" class="btn btn-danger btn-lg pull-right" name="deletebayloc" id="deletebayloc">Delete Bay Location</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
