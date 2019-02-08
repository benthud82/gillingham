
<!-- Add Comment Modal -->
<div id="addcommentmodal" class="modal fade " role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Item Comment</h4>
            </div>
            <form class="form-horizontal" id="postitemcomment">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Comment Title: </label>
                        <div class="col-md-9">
                            <input type="text" name="descriptionmodal" id="descriptionmodal" class="form-control" placeholder="Enter High-level Description..." tabindex="1"/>
                        </div>
                    </div>
                    <div class="form-group hidden">

                        <div class="col-md-9">
                            <input type="text" name="itemmodal" id="itemmodal" class="form-control"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Enter Detailed Comment: </label>
                        <div class="col-md-9">
                            <textarea rows="3" placeholder="" class="form-control" id="commentmodal" name="commentmodal" tabindex="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-lg pull-left" name="additemcomment" id="additemcomment">Add Item Comment</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
