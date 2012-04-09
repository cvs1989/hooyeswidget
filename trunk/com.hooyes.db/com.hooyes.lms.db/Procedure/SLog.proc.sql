DROP PROC [SLog]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2012-04-09
-- Update date: 2012-04-09
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[SLog]
	@MID int = 0, 
	@Code int = 0,
	@Message varchar(200)
AS
	INSERT INTO [Log]
			   ([MID]
			   ,[Code]
			   ,[Message])
		 VALUES
			   (@MID
			   ,@Code
			   ,@Message)
RETURN 0