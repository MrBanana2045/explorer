Upload section via Instagram link Save this source code in an html file
```html
<form method="post" action="video_server.php">
    <div class="form-group">
        <input type="text" name="url" required>
    </div>
    
    <div class="form-group">
        <label for="description">Dis:</label>
        <textarea name="des"></textarea>
    </div>
    
    <button type="submit" name="submit">POST</button>
</form>
```

Save this source code in index.php to display videos from the folder.
```php
 <div class="container">
        <?php if (!empty($videos)): ?>
            <div class="video-list">
                <?php foreach ($videos as $video): ?>
                    <?php
                    $video_path = $video['video_path'] ?? '';
                    if (!empty($video_path) && file_exists($video_path)):
                    ?>
                    <div class="video-item">
                        <video controls preload="metadata">
                            <source src="<?php echo htmlspecialchars($video_path); ?>" type="video/mp4">
                        </video>
                        <div class="video-info">
                            <p class="description"><?php echo htmlspecialchars($video['description'] ?? 'Not Dis'); ?></p>
                            <p class="meta">
                                by: <strong><?php echo htmlspecialchars($video['user'] ?? 'Unk'); ?></strong> 
                                time: <strong><?php echo htmlspecialchars(date('Y/m/d', strtotime($video['time']))); ?></strong>
                            </p>
                        </div>
                    </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
```
