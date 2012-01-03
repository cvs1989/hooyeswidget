DROP PROC [Get_MyPaper]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2012-01-02
-- Update date: 2012-01-02
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Get_MyPaper]
	@MID int = 0
AS
	SELECT * 
	FROM Question 
RETURN 0