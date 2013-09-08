-- DROP PROC [Update_MyQuestion]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2012-01-02
-- Update date: 2012-01-02
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Update_MyQuestion]
	@MID int = 0, 
	@QID int = 0, 
	@Answer nvarchar(50),
	@Score int = 0,
	@Flag  int = 0
AS
	INSERT INTO [My_Question]([MID],[QID],[Answer],[Score],[Flag])VALUES
			   (@MID
			   ,@QID
			   ,@Answer
			   ,@Score
			   ,@Flag)
RETURN 0