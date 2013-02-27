DROP PROC [Check_Login]
GO
-- =============================================
-- Version:     1.0.0.4
-- Author:		hooyes
-- Create date: 2011-12-18
-- Update date: 2013-02-27
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Check_Login]
	 @LoginID varchar(30) = null
	,@LoginPWD varchar(20) = null
	,@Code int = 0 output
	,@Message varchar(200) = '' output
AS
	--SET NOCOUNT ON;
	DECLARE @MID int
	IF @LoginID is null OR @LoginPWD is null 
	BEGIN
		SET @Code = 202
		SET @Message = 'ooops,login failure'
		SET @MID = -1
	END
	ELSE
	BEGIN
		SELECT @MID = MID 
		FROM Member 
		WHERE IDSN = @LoginID 
				AND IDCard = @LoginPWD
				AND ([ExpireDate] >=GETDATE() OR [ExpireDate] IS NULL)
						
		IF @MID is not null
		BEGIN
			SET @Code = 0
			SET @Message = 'success'
			IF EXISTS(SELECT * FROM dbo.Report WHERE MID = @MID)
			BEGIN
				EXECUTE Task_EvaluteCourses @MID
			END
		END
		ELSE
		BEGIN
			IF EXISTS(select 1 
				from dbo.Member 
				where IDSN = @LoginID 
						AND ([ExpireDate] >=GETDATE() OR [ExpireDate] IS NULL)
						)
			BEGIN
				SET @Code = 201
				SET @Message = 'LoginID or LoginPWD incorrect'
			END
			ELSE
			BEGIN
				SET @Code = 200
				SET @Message = 'LoginID not exists'
			END
			SET @MID = -1
		END
	END

RETURN @MID