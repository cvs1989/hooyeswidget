-- DROP PROC [Get_Member]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2011-12-18
-- Update date: 2011-12-18
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Get_Member]
	@MID int = 0
AS
	SELECT * FROM Member WHERE MID= @MID 
RETURN 0