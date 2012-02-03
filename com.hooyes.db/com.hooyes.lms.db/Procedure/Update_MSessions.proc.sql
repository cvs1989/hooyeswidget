DROP PROC [Update_MSessions]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2012-02-03
-- Update date: 2012-02-03
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Update_MSessions]
	@MID int, 
	@SessionId nvarchar(88),
	@IP varchar(50),
	@Created datetime = null

AS
	IF EXISTS( SELECT * FROM MSessions WHERE MID = @MID )
	BEGIN
		UPDATE MSessions
		SET SessionId = @SessionId 
			,IP = @IP
		WHERE MID = @MID
	END
	ELSE
	BEGIN
		IF @Created is null 
			SET @Created = GETDATE()
		INSERT INTO [MSessions]
           ([MID]
           ,[SessionId]
           ,[IP]
           ,[Created])
		VALUES
           (@MID
           ,@SessionId
           ,@IP
           ,@Created)
	END
RETURN 0