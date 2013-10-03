-- DROP PROC [Get_MyCourses]
GO
-- =============================================
-- Version:     1.0.0.2
-- Author:		hooyes
-- Create date: 2011-12-18
-- Update date: 2013-09-29
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Get_MyCourses] @MID INT, @CID INT
AS 
    DECLARE @Year INT 

    SELECT  a.CID ,
            a.Name ,
            Minutes = ISNULL(b.Minutes, 0) ,
            Second = ISNULL(b.Second, 0) ,
            Status = ISNULL(b.Status, 0) ,
            Validate = ISNULL(b.Validate, 0) ,
            Length = ISNULL(a.Length, 0),
			[Year] = a.Year
    FROM    ( SELECT    *
              FROM      Courses
              WHERE     CID = @CID
            ) AS a
            LEFT OUTER JOIN ( SELECT    *
                              FROM      My_Courses
                              WHERE     CID = @CID
                                        AND MID = @MID
                            ) AS b ON a.CID = b.CID

    SELECT  @Year = [Year]
    FROM    Courses
    WHERE   CID = @CID

    EXECUTE Task_EvaluteContents @MID, @CID

    EXECUTE Task_EvaluteCourses @MID, @Year

    RETURN 0