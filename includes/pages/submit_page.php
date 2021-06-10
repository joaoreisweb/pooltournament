<div>
<!-- Modal Update Match submit -->
<div class="modal fade" id="modal_submit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header pb-0">
                <h5 class="modal-title mb-2" style="font-weight:300">Update Match</h5>
            </div>
            <div class="modal-body" style="background-color:#f7f7f7; box-shadow: 0 0px 10px 0px #b9b9b9;">
                <form id="form_match">
                    <div class="form-group">

                        <div class="form-group">
                            <label for="select_matches">Select Match to Update</label>
                            <select id="select_matches" name="select_matches" class="form-control"></select>
                        </div>

                        <div class="form-group">
                            <label for="select_status">Select Status</label>
                            <select id="select_status" name="select_status" class="form-control">
                                <option value="Played">Played</option>
                                <option value="Absent">Absent</option>
                                <option value="Waiting">Waiting</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="select_winner">Select Winner</label>
                            <select id="select_winner" name="select_winner" class="form-control"></select>
                        </div>

                        <div class="form-group">
                            <label for="select_looser_balls"><span id="looser_name">Looser</span> - Number of balls left</label>
                            <select id="select_looser_balls" name="select_looser_balls" class="form-control"></select>
                        </div>
                    </div>
            </div>
            </form>
            <div class="modal-footer">
                <button id="btn_update_match" type="button" class="btn btn-warning" data-dismiss="modal" aria-label="Close" style="padding: 5px;font-size:14px;border:1px solid #cccccc;">UPDATE</button>
                <button id="btn_close_match" value="_close" type="button" class="btn btn-outline-default" data-dismiss="modal" aria-label="Close" style="padding: 0 2px;font-size:14px;border:1px solid #cccccc;"><small style="font-size:10px;display:block;color:#cccccc">EXIT</small></button>
            </div>


        </div>
    </div>
</div>
<!-- End Modal Update Match submit -->
</div>