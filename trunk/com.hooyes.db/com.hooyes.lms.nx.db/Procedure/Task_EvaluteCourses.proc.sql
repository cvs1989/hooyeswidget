-- =============================================
-- Version:     1.0.0.4
-- Author:		hooyes
-- Create date: 2012-02-15
-- Update date: 2013-10-12
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Task_EvaluteCourses]
    @MID INT ,
    @Year INT = 2013
AS 
    DECLARE @Minutes DECIMAL

    /* jx 没有必选修 */

	/*  @Year 总的学时数 */
    SELECT  @Minutes = ISNULL(SUM(CASE WHEN myc.Status = 1 THEN c.Length * 45
                                ELSE myc.Minutes
                           END),0)
    FROM    My_Courses myc
            INNER JOIN Courses c ON c.CID = myc.CID
    WHERE   myc.MID = @MID
            AND c.[Year] = @Year

    IF EXISTS ( SELECT  1
                FROM    Report
                WHERE   MID = @MID
                        AND [Year] = @Year
                        AND ([Minutes] != @Minutes OR [Minutes] is null) )
        OR NOT EXISTS ( SELECT  1
                        FROM    Report
                        WHERE   MID = @MID
                                AND [Year] = @Year ) 
        BEGIN

            EXECUTE [Update_Report] @MID = @MID, @Year = @Year,
                @Minutes = @Minutes
        END   

    RETURN 0