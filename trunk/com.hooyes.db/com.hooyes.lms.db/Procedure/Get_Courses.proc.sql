DROP PROC [Get_Courses]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2011-12-18
-- Update date: 2011-12-18
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Get_Courses]
	@CID int
AS
	SELECT * FROM Courses WHERE CID = @CID
RETURN 0