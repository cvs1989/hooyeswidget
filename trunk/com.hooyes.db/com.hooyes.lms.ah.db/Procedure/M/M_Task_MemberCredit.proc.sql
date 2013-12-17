-- DROP PROC [M_Task_MemberCredit]
GO
-- =============================================
-- Version:     1.0.0.6
-- Author:		hooyes
-- Create date: 2012-04-25
-- Update date: 2013-12-05
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[M_Task_MemberCredit]

AS
	DECLARE @MID int,
			@Year int,
	        @score int = 65
	DECLARE MCursor CURSOR LOCAL STATIC FOR

	SELECT M.MID,
		   [Year] = 2013
	FROM Member M  WHERE flag = 1

	OPEN MCursor 
    FETCH NEXT FROM MCursor INTO @MID,@Year
    WHILE (@@FETCH_STATUS = 0)
    BEGIN
  
	

	-- 2013 年的学员
	IF @Year = 2013
	BEGIN
			EXECUTE [M_Update_Courses] @MID,1
			EXECUTE [M_Update_Courses] @MID,2
			EXECUTE [M_Update_Courses] @MID,8
			EXECUTE [M_Update_Courses] @MID,26
			EXECUTE [M_Update_Courses] @MID,39
			EXECUTE [M_Update_Courses] @MID,46
	END

	-- 评估
	EXECUTE [Task_EvaluteCourses] @MID

	
    

	FETCH NEXT FROM MCursor INTO @MID,@Year
    END
	CLOSE MCursor 
	DEALLOCATE MCursor 
RETURN 0