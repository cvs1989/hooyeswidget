-- DROP PROC [Check_Hours]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2012-02-29
-- Update date: 2012-02-29
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Check_Hours]
	 @MID int 
	,@Code int = 0 output
	,@Message varchar(200) = '' output
AS
	DECLARE @H int
	SELECT @H = DATEPART(hour,getdate())

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
	
RETURN 0