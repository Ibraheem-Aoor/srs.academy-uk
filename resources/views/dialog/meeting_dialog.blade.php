<?php
$deleteBtn = '';
if (Illuminate\Support\Facades\Auth::guard('web')->check() && Illuminate\Support\Facades\Auth::guard('web')->user()->is_admin) {
    $deleteBtn = '| <a href="javascript:void(0);" onclick="MeetingDialog.deleteRecording(\'[recordingId]\')" class="text-danger">Delete</a>';
}
?>
<!-- Meeting Modal -->
<div class="modal fade" id="meeting_dialog" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Room Meetings</h4>
                <a id="meeting_dialog_join_button" class="btn btn-warning" style="color: #000;" href="#"
                    target="_blank">Join Meeting</a>
            </div>
            <div class="modal-body">
                <input type="hidden" id="meeting_dialog_meeting_id" />
                <table id="meeting_dialog_recordings" class="table">
                    <thead>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Size</th>
                        <th></th>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    class MeetingDialog {

        static show(routineId) {

            // document.getElementById('meeting_dialog_meeting_id').value = routineId;

            var joinButton = document.getElementById('meeting_dialog_join_button');
            var recordingsTable = $('#meeting_dialog_recordings > tbody');

            joinButton.href = "#";
            recordingsTable.empty();

            if (!routineId) return;

            joinButton.href = '<?= url('') ?>/class-routine/meeting/join/' + routineId;

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '<?= csrf_token() ?>'
                },
                type: "GET",
                url: "<?= url('') ?>/class-routine/meeting/recording/" + routineId,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (!response) return;

                    /////////////////////////////////////////////////////////////////////

                    response.forEach(recording => {
                        var deleteBtn = `<?= $deleteBtn ?>`;
                        deleteBtn.replace("[recordingId]", recording.id);

                        var row = `<tr>
                            <td>${recording.startTime}</td>
                            <td>${recording.endTime}</td>
                            <td>${recording.size}</td>
                            <td style="text-align: end;">
                                <a href="${recording.url}" target="_blank">View</a> |
                                <a href="<?= url('') ?>/class-routine/meeting/recording/download/${recording.id}">Download</a> ${deleteBtn}
                            </td>
                        </tr>`;

                        recordingsTable.append(row);
                    });

                    $("#meeting_dialog").modal('show');

                    /////////////////////////////////////////////////////////////////////

                },
                fail: function(jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown);
                },
                complete: function() {

                }
            });

        }

        static deleteRecording(recordingId) {

            if (!recordingId) return;

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '<?= csrf_token() ?>'
                },
                type: "GET",
                url: "<?= url('') ?>/class-routine/meeting/recording/delete/" + recordingId,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (!response) return;

                    /////////////////////////////////////////////////////////////////////

                    if (response == true) {
                        $("#meeting_dialog").modal('hide');
                    }

                    /////////////////////////////////////////////////////////////////////

                },
                fail: function(jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown);
                },
                complete: function() {

                }
            });

        }


    }
</script>
