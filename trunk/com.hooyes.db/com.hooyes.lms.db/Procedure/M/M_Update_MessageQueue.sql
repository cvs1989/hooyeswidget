DROP PROC [M_Update_MessageQueue]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2013-01-27
-- Update date: 2014-07-28
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[M_Update_MessageQueue]
	@ID int = 0,
	@Flag int = 0
AS
	UPDATE MessageQueue 
		SET Flag = @Flag
		   ,UpdateDate  = GETDATE()
    WHERE ID = @ID

RETURN 0
