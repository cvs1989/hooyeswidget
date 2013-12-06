-- DROP PROC [M_Task_MemberCredit]
GO
-- =============================================
-- Version:     1.0.0.6
-- Author:		hooyes
-- Create date: 2012-04-25
-- Update date: 2013-12-05
-- Desc:  jx
-- =============================================
CREATE PROCEDURE [dbo].[M_Task_MemberCredit]
AS 
    DECLARE @MID INT ,
        @Year INT ,
        @Type INT ,
        @ID INT ,
        @score INT = 65
    DECLARE MCursor CURSOR LOCAL STATIC
    FOR
        SELECT  M.MID ,
                MC.Year ,
                M.Type ,
                MC.ID
        FROM    MemberCredit MC
                INNER JOIN Member M ON ( MC.IDCard = M.IDCard
                                         AND MC.IDSN = M.IDSN
                                       )
        WHERE   MC.flag = 0
        ORDER BY MC.tstamp 

    OPEN MCursor 
    FETCH NEXT FROM MCursor INTO @MID, @Year, @Type, @ID
    WHILE ( @@FETCH_STATUS = 0 ) 
        BEGIN
  
	-- 2013 年的学员
            IF @Year = 2013 
                BEGIN
                    EXECUTE [M_Update_Courses] @MID, 6101
                    EXECUTE [M_Update_Courses] @MID, 6102
                    EXECUTE [M_Update_Courses] @MID, 6108
                    EXECUTE [M_Update_Courses] @MID, 6112
                    EXECUTE [M_Update_Courses] @MID, 6109
                    EXECUTE [M_Update_Courses] @MID, 6110
                END    

	-- 2012 年的学员
            IF @Year = 2012 
                BEGIN
                    EXECUTE [M_Update_Courses] @MID, 6001
                    EXECUTE [M_Update_Courses] @MID, 6002
                    EXECUTE [M_Update_Courses] @MID, 6005
                    EXECUTE [M_Update_Courses] @MID, 6006
                    EXECUTE [M_Update_Courses] @MID, 6007
                    EXECUTE [M_Update_Courses] @MID, 6008
                    EXECUTE [M_Update_Courses] @MID, 6009
                    EXECUTE [M_Update_Courses] @MID, 6010
                END

	-- 2011 年的学员
            IF @Year = 2011 
                BEGIN
                    EXECUTE [M_Update_Courses] @MID, 3001
                    EXECUTE [M_Update_Courses] @MID, 3002
                    EXECUTE [M_Update_Courses] @MID, 3003
                    EXECUTE [M_Update_Courses] @MID, 3004
                    EXECUTE [M_Update_Courses] @MID, 3005
                    EXECUTE [M_Update_Courses] @MID, 3006

                END

	-- 2010 年的学员
            IF @Year = 2010 
                BEGIN
                    EXECUTE [M_Update_Courses] @MID, 3011
                    EXECUTE [M_Update_Courses] @MID, 3012
                    EXECUTE [M_Update_Courses] @MID, 3013
                    EXECUTE [M_Update_Courses] @MID, 3014
                    EXECUTE [M_Update_Courses] @MID, 3015
                END

	-- 2009 年的学员
            IF @Year = 2009 
                BEGIN
                    EXECUTE [M_Update_Courses] @MID, 3016
                    EXECUTE [M_Update_Courses] @MID, 3017
                    EXECUTE [M_Update_Courses] @MID, 3018
                    EXECUTE [M_Update_Courses] @MID, 3019
                END
	-- 2008 年的学员
            IF @Year = 2008 
                BEGIN
                    EXECUTE [M_Update_Courses] @MID, 3020
                    EXECUTE [M_Update_Courses] @MID, 3021
                    EXECUTE [M_Update_Courses] @MID, 3022
                    EXECUTE [M_Update_Courses] @MID, 3023
                    EXECUTE [M_Update_Courses] @MID, 3024
                END
	-- 2007 年的学员
            IF @Year = 2007 
                BEGIN
                    EXECUTE [M_Update_Courses] @MID, 3025
                    EXECUTE [M_Update_Courses] @MID, 3026
                    EXECUTE [M_Update_Courses] @MID, 3027
                    EXECUTE [M_Update_Courses] @MID, 3028
                END

	-- 2006 年的学员
            IF @Year = 2006 
                BEGIN
                    EXECUTE [M_Update_Courses] @MID, 3029
                    EXECUTE [M_Update_Courses] @MID, 3030
                    EXECUTE [M_Update_Courses] @MID, 3031
                    EXECUTE [M_Update_Courses] @MID, 3032
                    EXECUTE [M_Update_Courses] @MID, 3033
                    EXECUTE [M_Update_Courses] @MID, 3034
                END
    
	-- 更新成绩
            IF NOT EXISTS ( SELECT  *
                            FROM    Report
                            WHERE   MID = @MID
                                    AND Score >= 60 ) 
                BEGIN

                    SET @score = 60
                    SELECT  @score = @score + RAND() * 21

                    EXECUTE [Update_Report] @MID, @score
                END
	-- 评估
            EXECUTE [Task_EvaluteCourses] @MID

            UPDATE  MemberCredit
            SET     flag = 1
            WHERE   ID = @ID
    

            FETCH NEXT FROM MCursor INTO @MID, @Year, @Type, @ID
        END
    CLOSE MCursor 
    DEALLOCATE MCursor 
    RETURN 0