DROP PROC [Get_MyCourses]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2011-12-18
-- Update date: 2011-12-18
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Get_MyCourses]
	@MID int
	,@CID int
AS
	 SELECT * FROM  dbo.My_Courses WHERE MID = @MID and CID = @CID
RETURN 0