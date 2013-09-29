-- DROP PROC [Get_MyCoursesList]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2011-12-22
-- Update date: 2013-09-23
-- Desc:
-- @Type 0 行政 1 企业
-- Cate  0 选修 1 必修  
-- Cate  100 行政选修,企业必修   101 行政必修,企业选修
-- Jx 不分企业与行政
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
                        oCate = c.Cate
              FROM      Courses c
                        INNER JOIN My_Products myp ON c.YEAR = myp.PID
                                                      AND myp.MID = @MID
                        LEFT OUTER JOIN ( SELECT    CID ,
                                                    [Minutes] ,
                                                    [Second] ,
                                                    [Status]
                                          FROM      My_Courses
                                          WHERE     MID = @MID
                                        ) myc ON myc.CID = c.CID
              WHERE     c.Year = @Year
            ) AS tb
    ORDER BY Cate DESC ,
            Sort ASC ,
            CID ASC
    RETURN 0