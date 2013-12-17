-- DROP PROC [Get_MyCoursesList]
GO
-- =============================================
-- Version: 1.0.0.2
-- Author:		hooyes
-- Create date: 2011-12-22
-- Update date: 2013-12-11
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Get_MyCoursesList]
    @MID INT ,
    @Year INT = 2012 ,
    @Type INT = 0
AS 
    SELECT  *
    FROM    ( SELECT    c.CID ,
                        c.Name ,
                        c.Year ,
                        Minutes = ISNULL(myc.Minutes, 0) ,
                        Second = ISNULL(myc.Second, 0) ,
                        Status = ISNULL(myc.Status, 0) ,
                        c.Type ,
                        c.Sort ,
                        c.Teacher ,
                        c.Length ,
                        Cate = c.Cate ,
                        oCate = c.Cate,
						myc.Validate,
						myc.Score
              FROM      Courses c
                        INNER JOIN My_Products myp ON c.YEAR = myp.PID
                                                      AND myp.MID = @MID
                        LEFT OUTER JOIN ( SELECT    CID ,
                                                    [Minutes] ,
                                                    [Second] ,
                                                    [Status],
													[Validate],
													[Score]
                                          FROM      My_Courses
                                          WHERE     MID = @MID
                                        ) myc ON myc.CID = c.CID
              WHERE     c.Year = @Year
            ) AS tb
    ORDER BY Cate DESC ,
            Sort ASC ,
            CID ASC
    RETURN 0