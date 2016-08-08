# Insert students from users in students table
INSERT INTO Students (uid)
  (
    SELECT u.uid
    FROM Users u
    WHERE privilege = 0
          AND u.uid NOT IN (
      SELECT s.uid
      FROM Students s)
  );

# Insert students in a given semester and section if they're not already in there
INSERT INTO StudentSemester (uid, sid, sectionName)
  (
    SELECT
      s.uid,
      8, #semester
      'A' #section
    FROM Students s
    WHERE s.uid NOT IN (
      SELECT st.uid
      FROM StudentSemester st)
  );

# This shows students who are registered in a semester and are in a group
CREATE VIEW RegisteredStudentsInGroup AS

  SELECT u.*, gt.* FROM Students s LEFT JOIN Users u ON u.uid = s.uid , Groups gt WHERE s.uid IN
                                                                                        (SELECT st.uid FROM StudentSemester st where st.uid IN
                                                                                                                                     (SELECT g.uid FROM GroupMembers g WHERE g.gid IN
                                                                                                                                                                             (SELECT gr.gid FROM Groups gr WHERE gt.gid = gr.gid)))
# The view can be used:  SELECT * FROM RegisteredStudentsInGroup
