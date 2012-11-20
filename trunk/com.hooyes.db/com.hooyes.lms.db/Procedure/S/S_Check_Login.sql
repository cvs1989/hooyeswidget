DROP PROC [S_Check_Login]
GO
-- =============================================
-- Version:     1.0.0.1
-- Author:		hooyes
-- Create date: 2012-11-20
-- Update date: 2012-11-20
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[S_Check_Login]
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
		SELECT @MID = MID FROM Member WHERE IDSN = @LoginID and IDCard = @LoginPWD
		IF @MID is not null
		BEGIN
			SET @Code = 0
			SET @Message = 'success'
		END
		ELSE
		BEGIN
			IF EXISTS(select * from dbo.Member where IDSN = @LoginID)
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