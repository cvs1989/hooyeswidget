-- DROP PROC [Check_Hours]
GO
-- =============================================
-- Version:     1.0.0.1
-- Author:		hooyes
-- Create date: 2012-02-29
-- Update date: 2013-09-12
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Check_Hours]
    @MID INT ,
    @Code INT = 0 OUTPUT ,
    @Message VARCHAR(200) = '' OUTPUT
AS 
    DECLARE @H INT
    SELECT  @H = DATEPART(hour, GETDATE())
	/*
	IF @H >= 23  or @H < 7 
	BEGIN
		SELECT @Code = 1
			  ,@Message ='pass time'
	END
	ELSE
	BEGIN
		SELECT @Code = 0
			  ,@Message ='normal'
	END
	*/
    SELECT  @Code = 1 ,
            @Message = 'pass time'
    RETURN 0