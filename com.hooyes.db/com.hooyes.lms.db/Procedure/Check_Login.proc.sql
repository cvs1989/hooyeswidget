﻿DROP PROC [Check_Login]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2011-12-18
-- Update date: 2012-02-02
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Check_Login]
	 @LoginID varchar(30)
	,@LoginPWD varchar(20)
	,@Code int = 0 output
	,@Message varchar(200) = '' output
AS
	DECLARE @MID int
	SELECT @MID = MID FROM Member WHERE IDSN = @LoginID and IDCard = @LoginPWD
	IF @MID is not null
	BEGIN
		SET @Code = 0
		SET @Message = 'success'
	END
	ELSE
	BEGIN
		IF EXISTS(select * from dbo.Member where IDCard = @LoginPWD or IDSN = @LoginID)
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

RETURN @MID