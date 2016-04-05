Suchworte:	x, y ...
Spalten: a, b, c ...

Filter:
	b: 1, 2 ...
	c: alpha, beta ...

SQL:
	SELECT a, b, c ...	
	(...)
	WHERE
		(b = 1 or b = 2 ...) and
		(c = alpha or c = beta ...) and
		(...)
		(
			(a = x and b = x and c = x ...) or
			(...)
			(a = x and b = x and c = y ...) or
			(...)
			(a = x and b = y and c = x ...) or
			(...)
			(a = x and b = y and c = y ...) or
			(...)
			(a = y and b = x and c = x ...) or
			(...)
			(a = y and b = x and c = y ...) or
			(...)
			(a = y and b = y and c = x ...) or
			(...)
			(a = y and b = y and c = y ...)
		)










