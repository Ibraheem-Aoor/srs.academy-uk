## NEW UPDATES###
1- Student:
    have: activeEnrolls
2- StudentEnroll
    requires: session_id,program_id only
3-SingleEnroll:
    requires: -session_id , program_id only
Transformations:
1- Session => Degree.
    StudentEnroll => Multiple "Active" Enrolls enabled so the student can study more than one degree at a time.
Fees: 
    - fees Now Per Degree  "Session"
Certifcates:
    - Certificates Now Per Degree  "Session"
