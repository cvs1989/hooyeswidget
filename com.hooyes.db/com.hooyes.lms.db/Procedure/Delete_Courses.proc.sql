-- DROP PROC [Delete_Courses]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2012-02-18
-- Update date: 2014-01-06
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Delete_Courses]
	@CID int = 0
AS
	--DELETE FROM Contents WHERE CID = @CID
	DELETE FROM Courses WHERE CID = @CID
RETURN 0