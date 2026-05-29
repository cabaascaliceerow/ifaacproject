<?php
include('includes/config.php');



/* =========================================================
   LOAD STUDENTS
========================================================= */
if(!empty($_POST["classid"]))
{
    $cid  = intval($_POST['classid']);
    $fid  = intval($_POST['faculty_id']);
    $did  = intval($_POST['dept_id']);

    if(!is_numeric($cid)){
        echo htmlentities("Invalid Class");
        exit;
    }

    $stmt = $dbh->prepare("
        SELECT StudentName, StudentId
        FROM tblstudents
        WHERE ClassId = :cid
        AND Faculty = :fid
        AND Department = :did
        ORDER BY StudentName
    ");

    $stmt->execute(array(
        ':cid' => $cid,
        ':fid' => $fid,
        ':did' => $did
    ));

    echo '<option value="">Select Student</option>';

    while($row = $stmt->fetch(PDO::FETCH_ASSOC))
    {
?>
        <option value="<?php echo htmlentities($row['StudentId']); ?>">
            <?php echo htmlentities($row['StudentName']); ?>
        </option>
<?php
    }
}



/* =========================================================
   LOAD SUBJECTS
========================================================= */
if(!empty($_POST["classid1"]))
{
    $cid1 = intval($_POST['classid1']);
    $fid  = intval($_POST['faculty_id']);
    $did  = intval($_POST['dept_id']);

    if(!is_numeric($cid1)){
        echo htmlentities("Invalid Class");
        exit;
    }

    $status = 0;

    $stmt = $dbh->prepare("
        SELECT 
            tblsubjects.SubjectName,
            tblsubjects.id
        FROM tblsubjectcombination
        JOIN tblsubjects
            ON tblsubjects.id = tblsubjectcombination.SubjectId

        WHERE tblsubjectcombination.ClassId = :cid
        AND tblsubjectcombination.FacultyId = :fid
        AND tblsubjectcombination.DepartmentId = :did
        AND tblsubjectcombination.status != :stts

        ORDER BY tblsubjects.SubjectName
    ");

    $stmt->execute(array(
        ':cid'  => $cid1,
        ':fid'  => $fid,
        ':did'  => $did,
        ':stts' => $status
    ));

    while($row = $stmt->fetch(PDO::FETCH_ASSOC))
    {
?>
        <div class="subject-row">

            <div>
                <strong>
                    <?php echo htmlentities($row['SubjectName']); ?>
                </strong>
            </div>

            <div style="display:flex; gap:10px; align-items:center;">

                <input type="number"
                       name="marks[]"
                       class="form-control marks-input"
                       min="0"
                       max="100"
                       required
                       placeholder="0 - 100">

                <span class="grade-badge">-</span>

            </div>

        </div>

<?php
    }
}



/* =========================================================
   CHECK DUPLICATE RESULT
========================================================= */
if(!empty($_POST["studclass"]))
{
    $id = $_POST['studclass'];

    $dta = explode("$", $id);

    $id  = $dta[0];
    $id1 = $dta[1];

    $query = $dbh->prepare("
        SELECT StudentId, ClassId
        FROM tblresult
        WHERE StudentId = :id1
        AND ClassId = :id
    ");

    $query->bindParam(':id1', $id1, PDO::PARAM_STR);
    $query->bindParam(':id',  $id,  PDO::PARAM_STR);

    $query->execute();

    if($query->rowCount() > 0)
    {
?>
        <p>
            <span style='color:red'>
                Result Already Declared.
            </span>

            <script>
                $('#submit').prop('disabled', true);
            </script>
        </p>
<?php
    }
}
?>