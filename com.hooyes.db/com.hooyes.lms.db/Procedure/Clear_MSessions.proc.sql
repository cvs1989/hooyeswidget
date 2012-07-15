DROP PROC [Clear_MSessions]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2012-07-15
-- Update date: 2012-07-15
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Clear_MSessions]
AS
	DELETE FROM MSessions 
	WHERE DATEDIFF(DAY,Created,GETDATE()) > 5
RETURN 0