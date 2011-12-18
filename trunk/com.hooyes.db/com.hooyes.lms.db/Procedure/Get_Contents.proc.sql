DROP PROC [Get_Contents]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2011-12-18
-- Update date: 2011-12-18
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Get_Contents]
	@CID int
AS
	SELECT * FROM dbo.Contents WHERE CID = @CID
RETURN 0