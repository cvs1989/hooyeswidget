DROP PROC [Clear_MSessions]
GO
-- =============================================
-- Version:     1.0.0.1
-- Author:		hooyes
-- Create date: 2012-07-15
-- Update date: 2012-07-27
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Clear_MSessions]
AS
	DELETE FROM MSessions 
	WHERE DATEDIFF(DAY,Created,GETDATE()) > 5

	DELETE FROM Timeline
	WHERE DATEDIFF(DAY,CreateDate,GETDATE())>=4

RETURN 0