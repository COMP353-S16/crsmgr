# Insert students from users in students table
INSERT INTO Students (uid)
  (SELECT
     u.uid
   FROM Users u
   WHERE privilege = 0
         AND u.uid NOT IN (SELECT
                             s.uid
                           FROM Students s)
  );

# Insert students in a given semester and section if they're not already in there
INSERT INTO StudentSemester (uid, sid, sectionName)
  (SELECT
     s.uid,
     8,       #semester
     'A'      #section
   FROM Students s
   WHERE s.uid NOT IN (SELECT
                         st.uid
                       FROM StudentSemester st)
  )