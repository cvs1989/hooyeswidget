-- DROP PROC [Task_EvaluteCourses]
GO
-- =============================================
-- Version:     1.0.0.1
-- Author:		hooyes
-- Create date: 2012-02-15
-- Update date: 2013-09-12
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Task_EvaluteCourses]
    @MID INT ,
    @Year INT = 2013
AS 
    DECLARE @Type INT = 0 ,
        @Compulsory DECIMAL ,
        @Elective DECIMAL ,
        @Minutes DECIMAL
	--SELECT @Year = [Year],@Type = [Type] FROM Member WHERE MID = @MID
    SELECT  @Compulsory = SUM(CASE Cate
                                WHEN 1 THEN CLength
                                ELSE 0
                              END) ,
            @Elective = SUM(CASE Cate
                              WHEN 0 THEN GMinutes
                              ELSE 0
                            END) / 45
    FROM    ( SELECT    c.CID ,
                        CLength = CASE WHEN Status = 1 THEN c.Length
                                       ELSE 0
                                  END ,
                        GMinutes = CASE WHEN Status = 1 THEN c.Length * 45
                                        ELSE ISNULL(myc.Minutes, 0)
                                   END ,
                        c.Length ,
                        Cate = CASE WHEN @Type = 0
                                         AND c.Cate = 100 THEN 0
                                    WHEN @Type = 0
                                         AND c.Cate = 101 THEN 1
                                    WHEN @Type = 1
                                         AND c.Cate = 100 THEN 1
                                    WHEN @Type = 1
                                         AND c.Cate = 101 THEN 0
                                    WHEN c.Cate = 99 THEN 0
                                    ELSE c.Cate
                               END
              FROM      Courses c
                        LEFT OUTER JOIN ( SELECT    CID ,
                                                    [Minutes] ,
                                                    [Second] ,
                                                    [Status]
                                          FROM      My_Courses
                                          WHERE     MID = @MID
                                        ) myc ON myc.CID = c.CID
              WHERE     c.Year = @Year
                        AND ( c.Type = @Type
                              OR c.Type = 3
                            )
            ) AS tb

	/*  @Year 总的学时数 */
    SELECT  @Minutes = SUM(CASE WHEN myc.Status = 1 THEN c.Length * 45
                                ELSE myc.Minutes
                           END)
    FROM    My_Courses myc
            INNER JOIN Courses c ON c.CID = myc.CID
    WHERE   myc.MID = @MID
            AND c.[Year] = @Year

    EXECUTE [Update_Report] @MID = @MID, @Year = @Year,
        @Compulsory = @Compulsory, @Elective = @Elective, @Minutes = @Minutes

    RETURN 0