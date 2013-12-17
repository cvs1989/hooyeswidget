-- DROP PROC [Update_Timeline]
GO
-- =============================================
-- Version:     1.0.0.2
-- Author:		hooyes
-- Create date: 2012-07-26
-- Update date: 2013-04-11
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Update_Timeline]
	@MID int = 0, 
	@Second float =0,
	@Record float =0,
	@DayID int = 0
AS
		IF @DayID = 0 
			SET @DayID = CONVERT(int, CONVERT(varchar(8),GETDATE(),112))
		UPDATE [Timeline]
			SET [Second] = [Second]+ @Second
				,[Record] = [Record] + @Record
				,[UpdateDate] = GETDATE()
		WHERE [MID] = @MID 
			AND [DayID] = @DayID
        IF @@ROWCOUNT = 0
		BEGIN
			INSERT INTO [Timeline]
					   ([MID]
					   ,[DayID]
					   ,[CreateDate]
					   ,[UpdateDate]
					   ,[Second])
			VALUES
					   (@MID
					   ,@DayID
					   ,GETDATE()
					   ,null
					   ,@Second)
		END
RETURN 0