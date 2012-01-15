DROP PROC [Get_MyCourses]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2011-12-18
-- Update date: 2012-01-15
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Get_MyCourses]
	 @MID int
	,@CID int
AS
	SELECT c.CID
		,c.Name
		,myc.Minutes
		,myc.Second
		,myc.Status 
	FROM My_Courses myc 
		inner join Courses c on c.CID = myc.CID
	WHERE myc.MID = @MID 
		and c.CID = @CID
		and myc.CID = @CID
RETURN 0