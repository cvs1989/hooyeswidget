---- =============================================
---- Author: hooyes
---- Create date: 2013-07-05
---- Update date: 2013-07-05
---- Description: 字符串分割函数
---- =============================================
CREATE FUNCTION [dbo].[split]
    (
      @Str VARCHAR(8000) ,
      @Separator VARCHAR(10)
    )
RETURNS @returntable TABLE
    (
      id INT IDENTITY(1, 1) ,
      value VARCHAR(500)
    )
AS
    BEGIN
        DECLARE @i INT
        SET @Str = RTRIM(LTRIM(@Str))
        SET @i = CHARINDEX(@Separator, @Str)
        WHILE @i >= 1
            BEGIN
                INSERT  @returntable
                VALUES  ( LEFT(@Str, @i - 1) )
                SET @Str = SUBSTRING(@Str, @i + 1, LEN(@Str) - @i)
                SET @i = CHARINDEX(@Separator, @Str)
            END
        IF @Str <> '\'
            BEGIN
                INSERT  @returntable
                VALUES  ( @Str )
            END
        RETURN
    END