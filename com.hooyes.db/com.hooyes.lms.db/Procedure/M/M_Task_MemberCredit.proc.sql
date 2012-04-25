DROP PROC [M_Task_MemberCredit]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2012-04-25
-- Update date: 2012-04-25
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[M_Task_MemberCredit]

AS
	DECLARE @MID int,
			@Year int,
			@Type int,
			@ID int
	DECLARE MCursor CURSOR FOR

	SELECT M.MID,
		   M.Year,
		   M.Type,
		   MC.ID
	FROM  MemberCredit MC
	inner join Member M on (MC.IDCard = M.IDCard AND MC.IDSN = M.IDSN)
	WHERE flag = 0
	ORDER by MC.tstamp 

	OPEN MCursor 
    FETCH NEXT FROM MCursor INTO @MID,@Year,@Type,@ID
    WHILE (@@FETCH_STATUS = 0)
    BEGIN

	-- 2012 年的学员
	IF @Year = 2012
	BEGIN
		IF @Type = 0
		BEGIN
			EXECUTE [M_Update_Courses] @MID,6001
			EXECUTE [M_Update_Courses] @MID,6002
			EXECUTE [M_Update_Courses] @MID,6005
			EXECUTE [M_Update_Courses] @MID,6006
			EXECUTE [M_Update_Courses] @MID,6007
			EXECUTE [M_Update_Courses] @MID,6008
			EXECUTE [M_Update_Courses] @MID,6009
			EXECUTE [M_Update_Courses] @MID,6010
		END

		IF @Type = 1
		BEGIN
			EXECUTE [M_Update_Courses] @MID,6003
			EXECUTE [M_Update_Courses] @MID,6004
			EXECUTE [M_Update_Courses] @MID,6024
			EXECUTE [M_Update_Courses] @MID,6025
			EXECUTE [M_Update_Courses] @MID,6026
		END

		-- 更新成绩
	  IF NOT EXISTS(SELECT * FROM Report WHERE MID = @MID and Score>=60)
	  BEGIN
		EXECUTE [Update_Report] @MID,75
	  END

	END

	UPDATE MemberCredit
		SET flag = 1 
	WHERE ID = @ID
    
	SELECT @MID,@Year,@Type,@ID
	FETCH NEXT FROM MCursor INTO @MID,@Year,@Type,@ID
    END
	CLOSE MCursor 
	DEALLOCATE MCursor 
RETURN 0